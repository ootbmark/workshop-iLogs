<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizReport;
use App\Models\UserQuiz;
use App\Services\ReportAnswersCreatorService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class QuizController extends Controller
{
    /** @var ReportAnswersCreatorService */
    private $answersCreator;

    public function __construct(ReportAnswersCreatorService $answersCreator)
    {
        $this->answersCreator = $answersCreator;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $prev_url = explode(request()->getHttpHost() . "/quiz/", url()->previous());

        $completed_quiz_questions_count = 0;

        $quiz_result = 0;

        if (isset($prev_url[1])) {

            $slug = strstr($prev_url[1], '?', true);

            $quiz = Quiz::where('slug', $slug)->first();

            $user_quiz = UserQuiz::byUserIDAndQuizID($request->user()->id, $quiz->id)->first();

            $props = $this->quizProperties($user_quiz);

            $completed_quiz_questions_count = $quiz->questions->count();

            $quiz_result = 100 * $props['right_answer_count'] / $completed_quiz_questions_count;
        }

        $quiz_answer_status = $this->quizAnswerStatus($quiz_result);


        $quizes = Auth::user()->getAllowedQuizes()->orderBy('id', 'desc')->where('is_active', 1)->whereHas('questions')->paginate(20);

        if (Session::get('check_quiz_complete')) {

            $completed = true;

            Session::remove('check_quiz_complete');

        } else {
            $completed = false;
        }

        return view('quiz.index', [
            'quizes' => $quizes,
            'right_answer_count' => isset($props['right_answer_count']) ? $props['right_answer_count'] : 0,
            'completed' => $completed,
            'completed_quiz_questions_count' => $completed_quiz_questions_count,
            'quiz_answer_status' => $quiz_answer_status,
            'answer_text' => isset($props) ? $props['answer_text'] : false,
            'is_answered' => isset($props) ? $props['is_answered'] : false,
        ]);
    }

    /**
     * @param int $quiz_result
     * @return string
     */
    public function quizAnswerStatus(int $quiz_result)
    {
        if ($quiz_result == 0) {
            return 'Try again';
        } elseif ($quiz_result <= 40 && $quiz_result > 0) {
            return 'Not bad';
        } elseif ($quiz_result > 40 && $quiz_result <= 70) {
            return 'Good';
        } else {
            return 'Perfect';
        }
    }

    /**
     * @param null $user_quiz
     * @return array
     */
    public function quizProperties($user_quiz = null)
    {
        $is_answered = false;

        $answer_text = false;

        $right_answer_count = 0;

        if ($user_quiz) {
            $quiz_answers = QuizAnswer::whereHas('user_quiz', function ($query) use ($user_quiz) {
                return $query->where('id', $user_quiz->id);
            })->get();

            if (!$quiz_answers->isEmpty()) {
                $is_answered = true;
            }

            $quiz_answers->groupBy('question_id')->each(function ($answer) use (&$right_answer_count, &$answer_text) {

                $answers = collect();

                $answer->each(function ($quiz_answer) use (&$right_answer_count, &$answer_text, $answers) {

                    if ($quiz_answer->text) {
                        $answer_text = true;
                    }

                    if ($quiz_answer->answer) {
                        $answers->add($quiz_answer->answer);
                    }
                });

                $filtered_answer = $answers->filter(function ($answer, $key) {
                    return $answer->is_right === false;
                });

                if ($filtered_answer->isEmpty()) {
                    $right_answer_count++;
                }
            });
        }
        return [
            'is_answered' => $is_answered,
            'answer_text' => $answer_text,
            'right_answer_count' => $right_answer_count
        ];
    }


    /**
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function show(Request $request, string $slug)
    {
        $course_id = $request->has('course') ? $request->course : null;
        $time_limit = '';
        $quiz = Quiz::whereSlug($slug)->first();
        if (!$quiz) {
            abort(404);
        }
        $userQuiz = UserQuiz::byUserIDAndQuizID($request->user()->id, $quiz->id)->first();
        $props = $this->quizProperties($userQuiz);
        $is_participate = $course_id ? [] : $this->checkIfUserParticipateQuiz($request->user()->id, $quiz->id);
        $completed_quiz_questions_count = $quiz->questions->count();
        $quiz_result = 100 * $props['right_answer_count'] / $completed_quiz_questions_count;
        $quiz_answer_status = $this->quizAnswerStatus($quiz_result);
        if ($userQuiz && $userQuiz->is_completed && !$request->has('new') && is_null($course_id)) {
            return redirect(route('quizes'));
        } else {
            if (empty($is_participate) || $request->has('participate') || !is_null($course_id)) {
                if (($userQuiz && $userQuiz->is_completed) || !$userQuiz) {
                    $userQuiz = UserQuiz::create([
                        'user_id' => $request->user()->id,
                        'quiz_id' => $quiz->id,
                    ]);
                }
            }
        }

        if ($userQuiz) {
            if (0 && !$userQuiz->is_completed) {
                $end_time = Carbon::parse($userQuiz->created_at)->addMinutes($quiz->time_limit);
                if ($end_time->isPast()) {
                    $userQuiz->update([
                        'is_completed' => true
                    ]);
                    return view('quiz_time_over', []);
                }
                $time_limit = $end_time->toDateTimeString();
            }
        }
        return view('quiz.show', [
            'quiz' => $quiz,
            'user_quiz_id' => $userQuiz->id,
            'answers' => $userQuiz ? $userQuiz->quiz_answers->groupBy('question_id')->toArray() : [],
            'time_limit' => $time_limit,
            'percent' => $userQuiz ? $this->calculateAnswersPercent($userQuiz->id) : 0,
            'is_participate' => $is_participate,
            'participate' => $request->has('participate') ? true : false,
            'answer_text' => $props['answer_text'],
            'is_answered' => $props['is_answered'],
            'right_answer_count' => $props['right_answer_count'],
            'quiz_answer_status' => $quiz_answer_status,
            'completed_quiz_questions_count' => $completed_quiz_questions_count,
            'course_id' => $course_id,
            'groups' => $quiz->groups()->pluck('name', 'groups_for_quiz.id')
        ]);
    }


    /**
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function preview(Request $request, string $slug)
    {
        $quiz = Quiz::whereSlug($slug)->first();
        if (!$quiz) {
            abort(404);
        }

        return view('quiz.preview', [
            'quiz' => $quiz,
            'answers' => [],
            'user_quiz_id' => 0
        ]);
    }


    /**
     * @param int $user_id
     * @param int $quiz_id
     * @return array
     */
    public function checkIfUserParticipateQuiz(int $user_id, int $quiz_id): array
    {
        $quiz = Quiz::find($quiz_id);

        $userQuiz = UserQuiz::byUserIDAndQuizID($user_id, $quiz_id)->first();

        if ($userQuiz && $userQuiz->is_completed) {

            $quiz_answers = QuizAnswer::whereHas('user_quiz', function ($query) use ($userQuiz) {
                return $query->where('id', $userQuiz->id);
            })->get();

            $right_answer_count = 0;

            $quiz_answers->each(function ($quiz_answer) use (&$right_answer_count) {

                $answer = Answer::find($quiz_answer->answer_id);

                if ($answer && $answer->is_right) {
                    $right_answer_count++;
                }
            });

            return [
                'right_answer_count' => $right_answer_count,
                'slug' => $quiz->slug
            ];
        }

        return [];
    }

    /**
     * @param int $user_quiz_id
     * @return int
     */
    public function calculateAnswersPercent(int $user_quiz_id): int
    {
        $user_quiz = UserQuiz::find($user_quiz_id);

        $quiz = Quiz::find($user_quiz->quiz_id);

        $questions_count = $quiz->questions->count();

        $answer_count = $user_quiz->quiz_answers->groupBy('question_id')->count();

        if ($questions_count === 0) {
            return 0;
        }

        return $answer_count / $questions_count * 100;
    }

    /**
     * @param Request $request
     * @param int $quiz_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function completeQuiz(Request $request, int $quiz_id)
    {
        Session::put('check_quiz_complete', true);

        $user_quiz = UserQuiz::byUserIDAndQuizID($request->user()->id, $quiz_id)->first();

        /*$user_quiz->update([
            'group_id' => $request->group_id
        ]);*/

        $quiz = Quiz::find($user_quiz->quiz_id);

        $user_quiz->update([
            'is_completed' => true
        ]);

        $duration = Carbon::now()->diffInSeconds($user_quiz->created_at);

        $target_date = Carbon::parse($request->target_date)->format('d M Y');

        $quiz_report = QuizReport::create([
            'quiz_id' => $user_quiz->quiz_id,
            'user_id' => $request->user()->id,
            'name' => $request->user()->name,
            'questions_count' => $quiz->questions->count(),
            'answers_count' => $user_quiz->quiz_answers->count(),
            'quiz_duration' => gmdate('i:s', $duration),
            'questions_answers' => "{$quiz->questions->count()} / {$user_quiz->quiz_answers()->groupBy('question_id')->get()->count()}",
            'status' => $request->is_priority,
            'status_effort' => $request->is_priority_effort,
            'priority' => $request->is_priority_priority,
            'focal_point' => $request->focal_point,
            'action_party' => $request->action_party,
            'target_date' => $target_date,
            'business_partner' => $request->business_partner,
            'is_verification_1' => $request->has('is_verification_1'),
            'is_verification_2' => $request->has('is_verification_2'),
            'is_verification_3' => $request->has('is_verification_3'),
            'is_verification_4' => $request->has('is_verification_4'),
            'is_verification_5' => $request->has('is_verification_5'),
            'group_id' => $request->group_id,
        ]);

        $quiz_answers = QuizAnswer::whereHas('user_quiz', function ($query) use ($user_quiz) {
            return $query->where('id', $user_quiz->id);
        })->get();

        $right_answer_count = 0;

        $quiz_answers->each(function ($quiz_answer) use ($quiz_report, &$right_answer_count) {

            $quiz_answer->quiz_report_id = $quiz_report->id;

            $quiz_answer->save();

            $answer = Answer::find($quiz_answer->answer_id);

            if ($answer && $answer->is_right) {
                $right_answer_count++;
            }
        });

        return redirect(route('forms.index.main'));
    }


    /**
     * @param Request $request
     * @param int $user_quiz_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function createQuizAnswer(Request $request, int $user_quiz_id)
    {
        $question_id = $request->question_id;

        $data = [
            'user_quiz_id' => $user_quiz_id,
            'question_id' => $question_id,
            'text' => $request->text,
            'answer_id' => null
        ];

        if (is_numeric($request->answer) && $request->type !== 'text' && $request->type !== 'textarea' && $request->type !== 'circling_text') {
            $data['answer_id'] = $request->answer;
        } else {
            $data['text'] = $request->answer;
        }

        if ($request->type === 'circling' || $request->type === 'circling_text') {

            $answer_id = $data['answer_id'];

            $data['answer_id'] = null;

            $quiz_answer = QuizAnswer::byUserQuizQuestionAndAnswer($user_quiz_id, $request->question_id, null, true)->first();

            if ($quiz_answer) {
                if ($data['text']) {
                    $quiz_answer->update([
                        'text' => $data['text']
                    ]);
                }
                if ($answer_id){
                    $quiz_answer->answers()->attach($answer_id);
                }

            } else {
                $quizAnswer = QuizAnswer::create($data);

                if ($answer_id) {
                    $quizAnswer->answers()->attach($answer_id);
                }


            }

            $percent = $this->calculateAnswersPercent($user_quiz_id);

            return response()->json([
                'success' => true,
                'percent' => $percent,
                'quiz_answer_id' => ($quizAnswer->id ?? '')
            ]);

        }

        if ($request->type === 'dropdown' ||
            $request->type === 'radio' ||
            $request->type === 'text' ||
            $request->type === 'textarea') {
            $quiz_answer = QuizAnswer::byUserQuizQuestionAndAnswer($user_quiz_id, $request->question_id, null)
                ->first();

            if ($quiz_answer) {
                $quiz_answer->delete();
            }
        }


        QuizAnswer::create($data);

        $percent = $this->calculateAnswersPercent($user_quiz_id);

        return response()->json([
            'success' => true,
            'percent' => $percent
        ]);
    }

    /**
     * @param Request $request
     * @param int $user_quiz_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteQuizAnswer(Request $request, int $user_quiz_id)
    {
        $answer_id = null;

        $quiz_answer_id = '';

        if ($request->has('answer')) {
            $answer_id = $request->answer;
        }

        $quiz_answer = QuizAnswer::byUserQuizQuestionAndAnswer($user_quiz_id, $request->question_id, $answer_id)
            ->first();

        if ($request->type === 'circling') {

            $quiz_answer = QuizAnswer::byUserQuizQuestionAndAnswer($user_quiz_id, $request->question_id, null, true)->first();

            if ($quiz_answer) {
                if (!$request->text) {
                    $quiz_answer->update([
                        'text' => $request->text
                    ]);
                }

                if ($answer_id) {
                    $quiz_answer->answers()->detach($answer_id);
                }

                if (!count($quiz_answer->answers) && !$request->text) {
                    $quiz_answer->delete();
                }

            }

            $percent = $this->calculateAnswersPercent($user_quiz_id);

            return response()->json([
                'success' => true,
                'percent' => $percent,
                'quiz_answer_id' => ($quizAnswer->id ?? '')
            ]);

        }

        if ($quiz_answer) {
            $quiz_answer->delete();
        }

        $percent = $this->calculateAnswersPercent($user_quiz_id);

        return response()->json([
            'success' => true,
            'percent' => $percent,
            'quiz_answer_id' => $quiz_answer_id
        ]);
    }

    public function editQuizAnswer(Request $request, int $quizReportId)
    {
        if ($request->has('edited')) {
            flash()->success('The report has been successfully edited.');

            return redirect(route('quiz-answer.edit', $quizReportId));
        }
        $report = QuizReport::with(['quiz.questions.answers.quiz_answer', 'quiz_answers'])->find($quizReportId);
        if ($report === null) {
            $archivedReport = QuizReport::withoutGlobalScope(QuizReport::SCOPE_ONLY_FRESH)->find($quizReportId);
            if ($archivedReport !== null) {
                return redirect()->route('quiz-answer.edit', $archivedReport->parent_id);
            } else {
                abort(404);
            }
        }

        $user = Auth::user();
        if (!$user->isAdmin() && $report->user_id !== $user->id) {
            abort(403, 'Access Denied');
        }

        $quiz = $report->quiz;
        if (!$quiz) {
            abort(404);
        }

        $questions_count = $quiz->questions->count();

        $answer_count = $report->quiz_answers->groupBy('question_id')->count();

        if ($questions_count === 0) {
            $percent = 0;
        } else {
            $percent = 100 * $answer_count / $questions_count;
        }

        return view('quiz.edit', [
            'quiz' => $quiz,
            'report' => $report,
            'groups' => $quiz->groups()->pluck('name', 'groups_for_quiz.id'),
            'percent' => (int)$percent,
            'answers' => $report->quiz_answers->groupBy('question_id')->toArray(),
        ]);
    }

    public function updateQuizAnswer(Request $request, int $quizReportId)
    {
        $report = QuizReport::findOrFail($quizReportId);

        $user = Auth::user();
        if (!$user->isAdmin() && $report->user_id !== $user->id) {
            abort(403, 'Access Denied');
        }

        $newReport = $report->replicate()->fill([
            'group_id' => $request->get('group'),
            'parent_id' => null,
            'questions_count' => $report->quiz->questions->count(),
            'answers_count' => $report->quiz_answers->count(),
            'questions_answers' => "{$report->quiz->questions->count()} / {$report->quiz_answers()->groupBy('question_id')->get()->count()}",
            'status' => $request->get('status'),
            'status_effort' => $request->get('status_effort'),
            'priority' => $request->get('priority'),
            'action_party' => $request->get('action_party'),
            'focal_point' => $request->get('focal_point'),
            'target_date' => $request->get('target_date'),
            'business_partner' => $request->get('business_partner'),
            'is_verification_1' => $request->has('is_verification_1'),
            'is_verification_2' => $request->has('is_verification_2'),
            'is_verification_3' => $request->has('is_verification_3'),
            'is_verification_4' => $request->has('is_verification_4'),
            'is_verification_5' => $request->has('is_verification_5'),
        ]);

        $newReport->save();

        $report->update([
            'parent_id' => $newReport->id,
        ]);

        foreach ($request->get('questions') as $questionId => $question) {
            $this->getAnswersCreator()->createAnswers($question, $questionId, $newReport->id);
        }

        return redirect(route('quiz-answer.edit', ['scribe_id' => $newReport->id, 'edited']));
    }

    private function getAnswersCreator(): ReportAnswersCreatorService
    {
        return $this->answersCreator;
    }
}

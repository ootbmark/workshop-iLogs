<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\GroupForQuiz;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizReport;
use App\Models\UserQuiz;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Yajra\DataTables\DataTables;

class FormsController extends Controller
{
    function index(Request $request, string $data)
    {
        try {
            $quiz = Quiz::where('quiz_code', base64_decode($data))->first();
            // flash()->success('Your action has been submitted. You can repeat this process to add more action points to the same log.');
            return view('forms.dashboard', compact('quiz'));
        } catch (\Throwable $th) {
            flash()->warning($th->getMessage());
            return back();
        }
    }
    function loginCode(Request $request)
    {
        try {
        } catch (\Throwable $th) {
            flash()->warning($th->getMessage());
        }
        return view('forms.index');
    }
    function verifyCode(Request $request)
    {
        $request->validate(
            ['formCode' => 'required'],
            ['formCode.required' => 'Survey code is required.']
        );

        try {
            $verifyCode = Quiz::where('quiz_code', $request->formCode)->first();
            if (!$verifyCode) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'formCode' => 'Invalid or expired survey code.'
                    ]);
            }
            return redirect()->route(
                'forms.view-code',
                base64_encode($request->formCode)
            );
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    public function show(Request $request, string $data)
    {
        if (empty($data)) {
            return redirect()->route('forms.view');
        }

        try {
            $quiz = Quiz::where('quiz_code', base64_decode($data))->first();
            $user_quiz_id = '';
            if (!$quiz) {
                flash()->warning('Quiz not found.');
                return redirect()->route('forms.view');
            }
            //return $request;
            $participant = [];
            $participantQuiz = null;
            $routeList = [1, 2];
            if ($request->filled('name')) {

                $participantName = strtoupper(trim($request->name));
                $groupId = base64_decode($request->groups);

                $user = UserQuiz::where('participate_name', 'like', "%{$participantName}%")
                    ->where('quiz_id', $quiz->id)
                    ->where('group_id', $groupId)
                    ->first();

                if (!$user) {
                    $user = UserQuiz::create([
                        'participate_name' => $participantName,
                        'quiz_id'          => $quiz->id,
                        'group_id'         => $groupId,
                    ]);

                    flash()->success('Welcome New Participant');
                }

                $participant = [
                    'participantName'  => $request->name,
                    'participantGroup' => $request->groups,
                    'participantID'    => $user->id,
                    'is_completed'     => $user->is_completed,
                ];
                $user_quiz_id =  $user->id;
                $participantQuiz = $user;
                $routeList = [
                    route('forms.store-answer', base64_encode($participant['participantID'] ?? 0)),
                    route('delete-quiz-answer', base64_encode($participant['participantID'] ?? 0)),
                ];
                if ($user->is_completed == 1) {
                    return redirect(route('forms.dashboard', base64_encode($quiz->quiz_code)));
                }
            }
            $percent = $participantQuiz
                ? $this->calculateAnswersPercent($participantQuiz->id)
                : 0;

            $answers = $participantQuiz
                ? $participantQuiz->quiz_answers->groupBy('question_id')->toArray()
                : [];

            $groups = $quiz->groups()->pluck('name', 'groups_for_quiz.id');

            $quizReport = [];

            return view('forms.show', compact(
                'quiz',
                'groups',
                'participant',
                'percent',
                'answers',
                'routeList',
                'user_quiz_id',
                'quizReport'
            ));
        } catch (\Throwable $th) {
            return $th->getMessage();
            return redirect()->with('error', $th->getMessage())->route('forms.view');
        }
    }
    function generate()
    {
        $quizzes = Quiz::where('is_active', true)->get();
        foreach ($quizzes as $key => $value) {
            // $value->quiz_code = strtoupper('QZ' . uniqid());
            $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $alphanumeric = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // 1. Get 2 random letters
            $randomLetters = substr(str_shuffle($letters), 0, 2);
            // 2. Get 4 random characters (numbers or letters)
            $randomPayload = substr(str_shuffle($alphanumeric), 0, 4);
            // 3. Combine them
            $value->quiz_code = $randomLetters . $randomPayload;
            // Output examples: FX65A9, QZ497C, AB12T9
            $value->save();
        }
        return 'Done';
    }

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
    function storeParticipantAnswer(Request $request,  string $userQuizId)
    {
        //return $request;
        $userQuizId = base64_decode($userQuizId);
        $type = $request->type;
        $questionId = $request->question_id;

        $isTextType = in_array($type, ['text', 'textarea', 'circling_text']);

        $data = [
            'user_quiz_id' => $userQuizId,
            'question_id'  => $questionId,
            'answer_id'    => null,
            'text'         => $request->text,
        ];

        if (is_numeric($request->answer) && !$isTextType) {
            $data['answer_id'] = $request->answer;
        } else {
            $data['text'] = $request->answer;
        }

        // Handle circling answers
        if (in_array($type, ['circling', 'circling_text'])) {

            $answerId = $data['answer_id'];
            $data['answer_id'] = null;

            $quizAnswer = QuizAnswer::byUserQuizQuestionAndAnswer(
                $userQuizId,
                $questionId,
                null,
                true
            )->first();

            if ($quizAnswer) {
                if (!empty($data['text'])) {
                    $quizAnswer->update([
                        'text' => $data['text']
                    ]);
                }

                if ($answerId) {
                    $quizAnswer->answers()->attach($answerId);
                }
            } else {
                $quizAnswer = QuizAnswer::create($data);

                if ($answerId) {
                    $quizAnswer->answers()->attach($answerId);
                }
            }

            return response()->json([
                'success'        => true,
                'percent'        => $this->calculateAnswersPercent($userQuizId),
                'quiz_answer_id' => $quizAnswer->id,
            ]);
        }

        // Single-answer question types
        if (in_array($type, ['dropdown', 'radio', 'text', 'textarea'])) {
            QuizAnswer::byUserQuizQuestionAndAnswer(
                $userQuizId,
                $questionId,
                null
            )->delete();
        }

        QuizAnswer::create($data);

        return response()->json([
            'success' => true,
            'percent' => $this->calculateAnswersPercent($userQuizId),
        ]);
    }
    function completeQuiz(Request $request, int $participant)
    {
        try {

            Session::put('check_quiz_complete', true);
            // Get the User Quiz Details
            $user_quiz = UserQuiz::find((int) $participant);
            $quiz = Quiz::find($user_quiz->quiz_id); // The main Quiz Details
            // Update the status of Participant Quiz
            /*  $user_quiz->update([
                'is_completed' => true
            ]); */
            $duration = Carbon::now()->diffInSeconds($user_quiz->created_at);
            $target_date = Carbon::parse($request->target_date)->format('d M Y');
            $data = [
                'quiz_id' => $user_quiz->quiz_id,
                'user_id' => null,
                'name' => $user_quiz->participate_name,
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
                'group_id' => $user_quiz->group_id,
            ];
            // return $data;
            $quiz_report = QuizReport::create($data);
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
            return redirect(route('forms.dashboard', base64_encode($quiz->quiz_code)));
        } catch (\Throwable $th) {
            flash()->warning($th->getMessage());
            return $th->getMessage();
            return back();
        }
    }
    public function scribesDataTable(Request $request, string $data)
    {
        $quizId = base64_decode($data);
        $quizReportTable = [];
        $quizReports = QuizReport::where('quiz_id', $quizId)->orderByDesc('id')->get();
        //return $quizReports;
        foreach ($quizReports as $key => $quiz) {
            $quizReportTable[] = array(
                'id' => $quiz->id,
                'name' => $quiz->name,
                'questionCount' => $quiz->questions_count,
                'answerCount' => $quiz->questions_answers,
                'quizTitle' => $quiz->quiz->title,
                'company' => $quiz->quiz->company->name,
                'value' => $quiz->status,
                'effort' => $quiz->status_effort,
                'report_status' => $quiz->report_status,
                'group_name' => $quiz->group->name ?? '',
                'first_question' =>  optional($quiz->quiz_answers->first())->text ?? '',
                'actions' =>  view('dashboard.quiz.custom._report_actions', ['quiz_report' => $quiz])->render()
            );
        }
        return DataTables::of($quizReportTable)
            ->rawColumns(['actions'])
            ->make(true);
        return $quizReportTable;
        return DataTables::of(
            QuizReport::query()
                ->select([
                    'id',
                    'quiz_id',
                    'group_id',
                    'created_at',
                    'quiz.title'
                ])
                ->with([
                    'quiz:id,title,company_id',
                    'quiz.company:id,name',
                    'quiz_answers:id,quiz_report_id,text,answer_id',
                    'group:id,name'
                ])
                ->where('quiz_id', $quizId)
                ->latest()
        )
            ->addColumn('group_number', function ($report) use (&$count, &$group, &$num) {
                if (!$report->group_id) {
                    return '';
                }

                if ($group != $report->group_id) {
                    $count++;
                    $group = $report->group_id;
                    $num = 0;
                }

                return $count . '.' . ++$num;
            })
            ->addColumn('group_name', function ($report) {
                return $report->group->name ?? '';
            })
            ->addColumn('first_answer', function ($report) {
                return optional($report->quiz_answers->first())->text ?? '';
            })
            ->addColumn('quiz_title', function ($report) {
                return $report->quiz->title ?? '';
            })
            ->addColumn('company', function ($report) {
                return $report->quiz->company->name ?? '';
            })
            ->addColumn('actions', function ($report) {
                return view('dashboard.quiz.custom._report_actions', [
                    'quiz_report' => $report
                ])->render();
            })
            ->rawColumns([
                'group_number',
                'group_name',
                'first_answer',
                'quiz_title',
                'company',
                'actions'
            ])
            ->make(true);
    }
    function shareLink(Request $request)
    {
        $quiz = Quiz::where('quiz_code', base64_decode($request->code))->first();
        return view('forms.shareLink', compact('quiz'));
    }
    function workshopDashboard(Request $request, $data)
    {
        $quiz = Quiz::where('quiz_code', base64_decode($data))->first();
        $quizAnswers = $quiz->groups()->get();
        $groupDetails = [];
        foreach ($quizAnswers as $key => $value) {
            $participantList = UserQuiz::where('quiz_id', $quiz->id)->where('group_id', $value->id)->get();
            $groupDetails[] = array(
                'name' => $value->name,
                'totalParticipants' => count($participantList),
                'participants' => $participantList
            );
        }
        // return  $groupDetails;
        return view('forms.workshop_dashboard', compact('quiz', 'groupDetails'));
    }
    function fetchWorkShopData($data)
    {
        try {
            $quiz = Quiz::where('quiz_code', base64_decode($data))->firstOrFail();
            $groups = $quiz->groups()->get();
            $totalParticipants = UserQuiz::where('quiz_id', $quiz->id)->count();
            $groupDetails = $groups->map(function ($group) use ($quiz, $totalParticipants) {
                $count = UserQuiz::where('quiz_id', $quiz->id)
                    ->where('group_id', $group->id)
                    ->count();
                return [
                    'id' => $group->id,
                    'name' => $group->name,
                    'totalParticipants' => $count,
                    'participationWeight' => $totalParticipants > 0
                        ? round(($count / $totalParticipants) * 100, 1)
                        : 0,
                ];
            });
            return DataTables::of($groupDetails)->make(true);
        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function viewQuizReport(string $code, string $scribe)
    {
        try {
            //return base64_decode($scribe);
            $quizReport = QuizReport::find(base64_decode($scribe));
            $quiz = Quiz::where('quiz_code', base64_decode($code))->first();
            $quizAnswers = QuizAnswer::whereHas('quiz_report', function ($query) use ($quizReport) {
                $query->where('id', $quizReport->id);
            })
                ->with(['question', 'answer', 'answers'])
                ->get();

            $quizAnswerList = [];

            foreach ($quizAnswers as $quizAnswer) {

                if (!$quizAnswer->question) {
                    continue;
                }

                $questionData = $quizAnswer->question;
                $answerData   = $quizAnswer->answer;

                $question = '';
                $answer   = '';
                $isRight  = false;

                // Question
                switch ($questionData->file_type) {
                    case 'image':
                        $question = '<img height="80" src="/storage/' . $questionData->file . '" alt="image">';
                        break;

                    case 'image_url':
                        $question = '<img height="100" src="' . $questionData->url . '" alt="image">';
                        break;

                    case 'youtube':
                        $question = '<iframe height="100" src="https://www.youtube.com/embed/' . $questionData->url . '" frameborder="0" allowfullscreen></iframe>';
                        break;

                    case 'video':
                        $question = '<video width="320" height="240" controls><source src="' . $questionData->url . '" type="video/mp4"></video>';
                        break;

                    default:
                        $question = $questionData->title;
                }

                // Answer
                if ($answerData) {
                    $answer = $answerData->file_type === 'image'
                        ? '<img height="80" src="/storage/' . $answerData->file . '" alt="image">'
                        : $answerData->title;

                    $isRight = (bool) $answerData->is_right;
                } else {
                    $answer = $quizAnswer->text;
                }

                // Circling Question Type
                if ($questionData->type === 'circling') {
                    $answer = '';

                    $answers = $quizAnswer->answers;
                    $count   = $answers->count();

                    foreach ($answers as $index => $item) {
                        if ($count === 1) {
                            $answer .= "<b>{$item->title}: </b>";
                        } elseif ($index === 0) {
                            $answer .= "<b>{$item->title}</b>";
                        } elseif ($index === $count - 1) {
                            $answer .= " / <b>{$item->title}: </b>";
                        } else {
                            $answer .= " / <b>{$item->title}</b>";
                        }
                    }

                    $answer .= $quizAnswer->text;
                }

                $quizAnswerList[] = [
                    'question_id' => $questionData->id,
                    'question'    => $question,
                    'answer'      => $answer,
                    'is_right'    => $isRight,
                ];
            }
            return view('forms.components.preview', compact('quiz', 'quizReport', 'quizAnswerList'));
            return array($code, $request);
        } catch (\Throwable $th) {
            //flash()->warning($th->getMessage());
            return $th->getMessage();
        }
    }
    function editQuizReport(string $code, string $scribe)
    {
        try {
            $quizReport = QuizReport::find(base64_decode($scribe));
            $quiz = Quiz::where('quiz_code', base64_decode($code))->first();
            $percent = 0;
            $answers = $quizReport->quizUser
                ? $quizReport->quizUser->quiz_answers->groupBy('question_id')->toArray()
                : [];
            $participant['participantID'] = $quizReport->quizUser->id;
            $routeList = [
                route('forms.store-answer', base64_encode($participant['participantID'] ?? 0)),
                route('delete-quiz-answer', base64_encode($participant['participantID'] ?? 0)),
            ];
            //return $quizReport;
            return view('forms.components.questioner', compact('quiz', 'quizReport', 'percent', 'answers', 'routeList', 'participant'));
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}

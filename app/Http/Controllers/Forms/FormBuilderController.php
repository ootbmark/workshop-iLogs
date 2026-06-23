<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Company;
use App\Models\GroupForQuiz;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizReport;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FormBuilderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $forms = Quiz::orderByDesc('id')->get();
        $groupList = GroupForQuiz::all();
        $companies = Company::all();
        $quizList = [];
        foreach ($forms as $key => $value) {
            $quiz = $value;
            $quizList[] = array(
                'id' => $value->id,
                'title' => $value->title,
                'description' => $value->description,
                'company' => $value->company->name,
                'groups' => $value->groups,
                'facilitator' => $value->quiz_user->completeName(),
                'quizCode' => $value->quiz_code,
                'actions' => view('version2.widgets._action', compact('quiz'))->render(),
                'shareLink' => route('forms.show-qrcode', ['code' => base64_encode($value->quiz_code)]),
                'copyLink' => route('forms.view-code', base64_encode($value->quiz_code))
            );
        }
        return view('version2.forms.view', compact('quizList', 'companies', 'groupList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $quiz = Quiz::create([
                'title'         => $request->title,
                'description'   => $request->description,
                'time_limit'    => $request->date,
                'answer_by_one' => $request->answer_by_one,
                'company_id'    => $request->company,
                'user_id'       => auth()->user()->id,
                'is_active'     => false,
                'slug'          => Quiz::getSlug($request->title),
            ]);
            if ($request->groups) {
                //return array_map('intval', $request->groups); //  $groupIds = $request->groups;

                $quiz->groups()->sync(array_map('intval', $request->groups));
            }
            return redirect(route('admin.builder.edit', base64_encode($quiz->id)))->with('success', 'Successfully Created');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $quiz = Quiz::find(base64_decode($id));
            if ($quiz) {
                $questions = $quiz->questions;
                // return $questions;
                $questionList = [];
                foreach ($questions as $key => $value) {
                    $answerList = [];
                    $answer =  $value->answers;
                    foreach ($answer as $key => $value1) {
                        $answerList[] = array(
                            'answerId' => $value1->id,
                            'title' => $value1->title,
                        );
                    }
                    $questionList[] = array(
                        'question_id' => $value->id,
                        'title' => $value->title,
                        'type' => $value->type,
                        'is_required' => $value->question_required,
                        'answer' => $answerList
                    );
                }
                $user_quiz_id = '';
                //return $request;
                $participant = [];
                $participantQuiz = null;
                $routeList = [1, 2];



                $answers = $participantQuiz
                    ? $participantQuiz->quiz_answers->groupBy('question_id')->toArray()
                    : [];

                $groups = $quiz->groups()->pluck('name', 'groups_for_quiz.id');
                $user_quiz_id = '';
                $quizReport = [];
                //return $questionList;
                return view('version2.forms.preview', compact(
                    'quiz',
                    'answers',
                    'quizReport',
                    'user_quiz_id'
                ));
            } else {
                back()->with('error', 'Missing Quiz');
            }
        } catch (\Throwable $th) {
            return $th->getMessage();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $quiz = Quiz::find(base64_decode($id));
            if ($quiz) {
                $groupList = GroupForQuiz::all();
                $companies = Company::all();
                //return $quiz->groups->pluck('id')->toArray();
                $questions = $quiz->questions;
                // return $questions;
                $questionList = [];
                foreach ($questions as $key => $value) {
                    $answerList = [];
                    $answer =  $value->answers;
                    foreach ($answer as $key => $value1) {
                        $answerList[] = array(
                            'optionCode' => $value1->id,
                            'option' => $value1->title,
                        );
                    }
                    $questionList[] = array(
                        'question' => $value->id,
                        'title' => $value->title,
                        'type' => $value->type,
                        'is_required' => $value->question_required,
                        'answer' => $answerList
                    );
                }
                //return $questionList;
                return view('version2.forms.edit', compact('quiz', 'groupList', 'companies', 'questionList'));
            } else {
                back()->with('error', 'Missing Quiz');
            }
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //return  $request;
        $quiz = Quiz::find($id);
        $quiz->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'time_limit'    => $request->date,
            'answer_by_one' => $request->answer_by_one,
            'company_id'    => $request->company,
            'user_id'       => auth()->user()->id,
            'is_active'     => false,
            'slug'          => Quiz::getSlug($request->title),
        ]);
        //return $request->groups;
        if ($request->groups) {
            //return array_map('intval', $request->groups); //  $groupIds = $request->groups;

            $quiz->groups()->sync(array_map('intval', $request->groups));
        }
        return back()->with('success', 'Successfully Update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    function updateVerification(Request $request)
    {
        try {
            $data = Quiz::find(base64_decode($request->quiz));
            $data[$request->id] = $request->verification;
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Successfully updated.'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Database update failed.'
            ], 500);
        }
        return $request;
    }
    function reviewReports($data)
    {
        try {
            $quiz = Quiz::find(base64_decode($data));
            $user_quiz_id = '';
            return view('version2.forms.reports', compact('quiz', 'user_quiz_id'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    function scribesDataTable($data)
    {
        $quizId = base64_decode($data);
        $quizReports = QuizReport::where('quiz_id', base64_decode($data))->orderByDesc('id')->get();
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
    }


    /* Form Builder */
    /* Store Question */
    function storeQuestion($data, Request $request)
    {
        try {
            $quiz = Quiz::find($data);
            $data = array(
                'quiz_id' => $quiz->id,
                'title' => $request->title,
                'type' => $request->type,
                'question_required' => $request->required,
            );
            if (!$request->questionCode) {

                $check =  Question::where($data)->first();
                if (!$check) {
                    $check =  Question::create($data);
                }
                if ($request->optionList) {
                    foreach ($request->optionList as $value) {

                        if (!empty($value['optionCode'])) {
                            Answer::find($value['optionCode'])->update([
                                'question_id' => $check->id,
                                'title' => $value['option'],
                            ]);
                        } else {
                            Answer::create([
                                'question_id' => $check->id,
                                'title' => $value['option'],
                            ]);
                        }
                    }
                }
            } else {
                $question = Question::find($request->questionCode);
                $question->update($data);
                if ($request->optionList) {
                    foreach ($request->optionList as $value) {

                        if (!empty($value['optionCode'])) {
                            Answer::find($value['optionCode'])->update([
                                'question_id' => $question->id,
                                'title' => $value['option'],
                            ]);
                        } else {
                            Answer::create([
                                'question_id' => $question->id,
                                'title' => $value['option'],
                            ]);
                        }
                    }
                }
            }
            return response(['data' => 'Successfully Save'], 200);
        } catch (\Throwable $th) {
            return response(['error' => $th->getMessage()], 200);
        }
    }
}

<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\GroupForQuiz;
use App\Models\Quiz;
use Illuminate\Http\Request;

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

            if ($request->filled('groups_ids')) {
                $groupIds = explode(',', $request->groups);


                $quiz->groups()->sync($groupIds);
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
        //
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
        //
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
}

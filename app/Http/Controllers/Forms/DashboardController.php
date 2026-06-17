<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\GroupForQuiz;
use App\Models\Quiz;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    function index()
    {
        $groupList = GroupForQuiz::all();
        $companies = Company::pluck('name', 'id')->toArray();
        $forms = Quiz::orderByDesc('id')->get();
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
        //return $quizList;
        $monitoring = array(
            'quizzes' => count($forms),
            'companies' => count($companies),
            'groups' => count($groupList)
        );
        return view('version2.dashboard', compact('monitoring', 'quizList'));
    }
}

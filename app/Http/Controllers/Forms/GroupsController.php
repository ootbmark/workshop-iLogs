<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\GroupForQuiz;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    function index()
    {
        $groupList = GroupForQuiz::orderBy('name')->get();
        return view('version2.group_view', compact('groupList'));
    }
    function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        try {
            GroupForQuiz::create(['name' => $request->name]);
            return back()->with('success', 'Successfully Added a Group');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    function update(Request $request, int $data)
    {
        try {
            GroupForQuiz::find($data)->update(['name' => $request->name]);
            return back()->with('success', 'Successfully Added a Group');
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
    function destroy(Request $request, int $data)
    {
        try {
            $group = GroupForQuiz::find($data);
            $group->delete();
            flash()->success(__('Group deleted!'));
        } catch (\Throwable $th) {
            return back()->with('error', $th->getMessage());
        }
    }
}

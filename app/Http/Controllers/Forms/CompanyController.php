<?php

namespace App\Http\Controllers\Forms;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    function index()
    {
        $companyList = Company::all();
        return $companyList;
        return view('version2.group_view', compact('companyList'));
    }
    function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        try {
            Company::create(['name' => $request->name]);
            return back()->with('success', 'Successfully Added a Group');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function update(Request $request, int $data)
    {
        try {
            Company::find($data)->update(['name' => $request->name]);
            return back()->with('success', 'Successfully Added a Group');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}

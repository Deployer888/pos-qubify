<?php

namespace App\Http\Controllers\Admin\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        return view('admin.companies.index');
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        // Implementation needed
        return redirect()->route('admin.companies.index');
    }

    public function show($id)
    {
        return view('admin.companies.show', compact('id'));
    }

    public function edit($id)
    {
        return view('admin.companies.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        // Implementation needed
        return redirect()->route('admin.companies.index');
    }

    public function destroy($id)
    {
        // Implementation needed
        return redirect()->route('admin.companies.index');
    }
}
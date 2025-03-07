<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{

    public function index()
    {
        return view('admin.users.list');
    }

    public function create()
    {
        return view('admin.users.create');
    }
    public function store(Request $request)
    {
        return redirect()->route('admin.users.index');
    }

    public function edit($id)
    {
        return view('admin.users.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        return redirect()->route('admin.users.index');
    }
}

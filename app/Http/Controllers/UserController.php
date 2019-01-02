<?php

namespace App\Http\Controllers;

use App\User;

use Illuminate\Http\Request;

class DummyController extends Controller
{
    public function index()
    {
        $user = User::paginate(15);
        return view('admin.user.index')->with(['items' => $user]);
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $req)
    {
        $user = new User();
        $user->id = $req->get('id');
$user->name = $req->get('name');
$user->email = $req->get('email');
$user->type_id = $req->get('type_id');

        $user->save();

        return redirect('admin/user');

    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit')->with(['item' => $user]);
    }

    public function update($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->update($request->except(['csrf_token']));
        $user->save();
        return redirect('admin/user');
    }

    public function delete(Request $req)
    {
        User::find($req->get('id'))->delete();
        return redirect('admin/user');
    }
}
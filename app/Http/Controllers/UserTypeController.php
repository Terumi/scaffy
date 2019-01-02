<?php

namespace App\Http\Controllers;

use App\UserType;

use Illuminate\Http\Request;

class DummyController extends Controller
{
    public function index()
    {
        $usertype = UserType::paginate(15);
        return view('admin.usertype.index')->with(['items' => $usertype]);
    }

    public function create()
    {
        return view('admin.usertype.create');
    }

    public function store(Request $req)
    {
        $usertype = new UserType();
        $usertype->id = $req->get('id');
        $usertype->type = $req->get('type');

        $usertype->save();

        return redirect('admin/usertype');

    }

    public function edit($id)
    {
        $usertype = UserType::findOrFail($id);
        return view('admin.usertype.edit')->with(['item' => $usertype]);
    }

    public function update($id, Request $request)
    {
        $usertype = UserType::findOrFail($id);
        $usertype->update($request->except(['csrf_token']));
        $usertype->save();
        return redirect('admin/usertype');
    }

    public function delete(Request $req)
    {
        UserType::find($req->get('id'))->delete();
        return redirect('admin/usertype');
    }
}
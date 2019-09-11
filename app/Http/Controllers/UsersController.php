<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function index()
    {
        $users = User::with('role')->paginate(10);
        $roles = Role::all();
        return view('admin.users', ['users' => $users, 'role' => $roles]);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function signIn(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (Auth::attempt($credentials)) {
            return redirect()->to('/admin/dashboard');
        }
        return redirect()->back()->with(['message' => 'Invalid login']);
    }


    public function logout()
    {
        if (Auth::check()) {
            Auth::logout();
            return redirect()->to('/login');
        }
        return redirect()->back();
    }


    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}

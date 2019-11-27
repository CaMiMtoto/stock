<?php

namespace App\Http\Controllers;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    public function index()
    {
        if (Auth::user()->role->name != 'admin') {
            abort(403, 'Unauthorized');
        }
        $users = User::with('role')->paginate(10);
        $roles = Role::all();
        return view('admin.users', ['users' => $users, 'roles' => $roles]);
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


    public function all(Request $request)
    {
        $columns = array(
            0 => 'name',
            1 => 'email',
            2 => 'role'
        );

        $totalData = User::count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        if (empty($request->input('search.value'))) {
            $users = User::with('role')->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->get();
        } else {
            $search = $request->input('search.value');

            $users = User::with('role')
                ->join('roles','roles.id','users.role_id')
                ->Where('users.email', 'LIKE', "%{$search}%")
                ->orWhere('users.name', 'LIKE', "%{$search}%")
                ->orWhere('roles.name', 'LIKE', "%{$search}%")
                ->offset($start)
                ->limit($limit)
                ->orderBy($order, $dir)
                ->select('users.*')
                ->get();

            $totalFiltered = $users->count();
        }

        $data = array();
        if (!empty($users)) {
            foreach ($users as $user) {
                $nestedData['id'] = $user->id;
                $nestedData['name'] = $user->name;
                $nestedData['email'] = $user->email;
                $nestedData['role'] = $user->role;
                $nestedData['created_at'] = date('j M Y h:i a', strtotime($user->created_at));
                $data[] = $nestedData;

            }
        }

        $json_data = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );
        return response()->json($json_data, 200);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'role' => 'required',
            'password' => 'required|min:4'
        ]);

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->role_id = $request['role'];
        $user->password = bcrypt($request['password']);
        $user->save();
        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        return response($user, 200);
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'role' => 'required'
        ]);

        $email = $request['email'];
        $password = $request->input('password');
        $id = $request->input('id');

        $obj = User::find($id);
        if (!$obj) return response()->json(['message' => 'Not found'], 404);

        $obj->name = $request['name'];
        $obj->email = $email;

        if (!empty($password)) {
            $obj->password = bcrypt($password);
        }

        $obj->role_id = $request['role'];
        $obj->update();
        return response()->json($obj, 200);
    }


    public function destroy(User $user)
    {
        $user->delete();
        return response(null, 204);
    }


    public function changePassword()
    {
        $user = Auth::user();
        return view('auth.change_password', compact('user'));
    }

    public function changePasswordPost(Request $request, User $user)
    {
        $this->validate($request, [
            'newPassword' => 'required|min:4|max:16',
        ]);
        if (Hash::check($request->oldPassword, $user->password)) {
            $user->password = Hash::make($request->newPassword);
            $user->update();
            Auth::logout();
            return redirect()->to('/login');
        }
        return view('auth.change_password', compact('user'))
            ->with(['error' => 'Invalid old password']);
    }
}

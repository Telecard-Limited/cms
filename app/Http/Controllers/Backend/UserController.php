<?php

namespace App\Http\Controllers\Backend;

use App\Role;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $role = Role::where("name", "superadmin")->first();
        $nimda = $role->users()->first();
        $query = User::all()->except(1);
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (User $user) {
                    $route = route('users.edit', $user->id);
                    return "<a href='$route' class='btn btn-warning'><i class='pe-7s-pen'></i></span> Edit</a>";
                })
                ->rawColumns(['edit'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'username', 'title' => 'Username'],
            ['data' => 'email', 'title' => 'Email'],
            ['data' => 'created_at', 'title' => 'Added At'],
            ['data' => 'updated_at', 'title' => 'Updated At'],
            ['data' => 'edit', 'title' => ''],
        ]);

        return view('architect.user.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,id']
        ]);

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
        ]);

        $user->roles()->attach($request->role);

        return redirect()->route('users.index')->with('status', 'User has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('architect.user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user, 'email')],
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user, 'username')],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'exists:roles,id']
        ]);

        $user->update([
            'username' => $request->username,
            'email' => $request->email,
            'name' => $request->name,
            'password' => $request->has('password') ? Hash::make($request->password) : $user->password
        ]);

        if($user->roles()->first()->id !== $request->role) {
            $user->roles()->sync([$request->role]);
        }

        return redirect()->route('users.index')->with('status', 'User has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}

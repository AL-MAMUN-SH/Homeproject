<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['users']=User::paginate(2);
        $data['serial']=ManagepaginationSerial($data['users']);
        $data['title'] = 'List of User';
        return view('admin.Users.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['title'] = 'Create New User';
        return view('admin.Users.create',$data);
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
            'name'=> 'required',
            'email'=> 'required|email|unique:users',
            'phone'=> 'required|unique:users',
            'password'=> 'required|min:6|confirmed',
        ]);
        $data=$request->all();
        $data['password']= Hash::make($data['password']);
        User::create($data);
        session()->flash('message','User Create Successfully');
        return redirect()->route('user.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $data['title'] =' User Edit';
          $data['user']=$user;
        return view('admin.Users.edite',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
       $request->validate([
           'name'=>'required',
           'email'=> 'required|email|unique:users,email,'.$user->id,
           'phone'=> 'required|unique:users,phone,'.$user->id,
       ]);
       $user->update($request->all());
       session()->flash('message','User Updated Successfully');
       return redirect()->route('user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        session()->flash('error','User Delete Successfully');
        return redirect()->route('user.index');

    }
}

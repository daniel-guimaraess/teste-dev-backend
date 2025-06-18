<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;

class UserRepository
{   
    public function index(Request $request){
        
        return User::all();
    }

    public function show(string|int $id){

        return User::find($id);
    }

    public function create(Request $request){
 
        User::create([
            'role_id' => $request['role_id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password']
        ]);
    }

    public function update(User $user, Request $request){

        $user->update([
            'role_id' => $request['role_id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password']
        ]);
    }

    public function delete(User $user){

        $user->delete();
    }
}
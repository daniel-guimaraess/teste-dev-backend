<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{   
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    public function index(Request $request){

        return $this->userService->index($request);
    }


    public function show(string|int $id){

        return $this->userService->show($id);
    }


    public function create(Request $request){

        Validator::make($request->all(), [
            'role_id' => 'required', 'integer',
            'name' => 'required', 'string', 'max:255',
            'email' => 'required', 'string', 'email', 'max:255', 'unique:users',
            'password' => 'required'

        ])->validate();

        return $this->userService->create($request);
    }


    public function update(string|int $id, Request $request){
        
        return $this->userService->update($id, $request);
    }


    public function delete(string|int $id){

        return $this->userService->delete($id);
    }
}
<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{   
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }


    public function index(Request $request){

        try {
            
            return $this->userRepository->index($request);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível exibir a lista de usuários'
            ], 500);
        } 
    }


    public function show(string|int $id){

        try {
            return $this->userRepository->show($id);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível exibir o usuário'
            ], 500);
        }
    }

    public function create(Request $request){

        try {
            $this->userRepository->create($request);

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível cadastrar o usuário'
            ], 500);
        } 
    }


    public function update(string|int $id, Request $request){
        
        try {
            if($user = User::find($id)){

                $this->userRepository->update($user, $request);

                return response()->json([
                    'message' => 'Usuário atualizado com sucesso'
                ], 200);
            }
            
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível atualizar o usuário',
                'error' => $th->getMessage()
            ], 500);
        }
    }


    public function delete(string|int $id){

        try {
            if($user = User::find($id)){

                $this->userRepository->delete($user);

                return response()->json([
                    'message' => 'Usuário removido com sucesso'
                ], 200);
            }
            
            return response()->json([
                'message' => 'Usuário não encontrado'
            ], 404);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível remover o usuário'
            ], 500);
        } 
    }
}
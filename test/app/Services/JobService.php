<?php

namespace App\Services;

use App\Models\Job;
use App\Repositories\JobRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobService
{   
    private $jobRepository;

    public function __construct(JobRepository $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }


    public function index(Request $request){

        try {
            return $this->jobRepository->index($request);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível exibir a lista de vagas'
            ], 500);
        } 
    }


    public function show(string|int $id){

        try {
            return $this->jobRepository->show($id);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível exibir a vaga'
            ], 500);
        }
    }

    public function create(Request $request){

        try {
            if(Auth::user()->role->id === 1)
            {
                $this->jobRepository->create($request);

                return response()->json([
                    'message' => 'Vaga cadastrada com sucesso'
                ], 200);
            }            

            return response()->json([
                'message' => 'Você não tem permissão para cadastrar uma vaga'
            ], 401);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível cadastrar a vaga' ,
                'error' => $th->getMessage()
            ], 500);
        } 
    }


    public function update(string|int $id, Request $request){
        
        try {
            if($job = Job::find($id)){

                $this->jobRepository->update($job, $request);

                return response()->json([
                    'message' => 'Vaga atualizada com sucesso'
                ], 200);
            }
            
            return response()->json([
                'message' => 'Vaga não encontrada'
            ], 404);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível atualizar a vaga'
            ], 500);
        }
    }


    public function delete(string|int $id){

        try {
            if($job = Job::find($id)){

                $this->jobRepository->delete($job);

                return response()->json([
                    'message' => 'Vaga removida com sucesso'
                ], 200);
            }
            
            return response()->json([
                'message' => 'Vaga não encontrada'
            ], 404);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível remover a vaga'
            ], 500);
        } 
    }


    public function finishJob(string|int $id){
        
        try {
            if(Auth::user()->role->id === 1)
            {
                if($job = Job::find($id)){

                    $this->jobRepository->finishJob($job);

                    return response()->json([
                        'message' => 'Vaga finalizada com sucesso'
                    ], 200);
                }
                
                return response()->json([
                    'message' => 'Vaga não encontrada'
                ], 404);
            }

            return response()->json([
                'message' => 'Você não tem permissão para cadastrar uma vaga'
            ], 401);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível finalizar a vaga'
            ], 500);
        }
    }


    public function pauseJob(string|int $id){
        
        try {
            if(Auth::user()->role->id === 1)
            {
                if($job = Job::find($id)){

                    if($job->status === 'finished'){
                        return response()->json([
                            'message' => 'Não foi possível pausar a vaga, pois ela ja foi finalizada'
                        ], 400);
                    }

                    $this->jobRepository->pauseJob($job);

                    return response()->json([
                        'message' => 'Vaga pausada com sucesso'
                    ], 200);
                }
                
                return response()->json([
                    'message' => 'Vaga não encontrada'
                ], 404);
            }

            return response()->json([
                'message' => 'Você não tem permissão para pausar esta vaga'
            ], 401);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível pausar a vaga'
            ], 500);
        }
    }
}
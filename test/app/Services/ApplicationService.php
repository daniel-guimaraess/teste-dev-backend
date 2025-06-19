<?php

namespace App\Services;

use App\Enums\StatusJob;
use App\Models\Application;
use App\Models\Job;
use App\Repositories\ApplicationRepository;
use Illuminate\Http\Request;

class ApplicationService
{   
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }


    public function getApplicationsByJobID(string|int $jobId){

        try {            
            return $this->applicationRepository->getApplicationsByJobID($jobId);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível exibir a lista de candidaturas'
            ], 500);
        } 
    }


    public function getApplicationsByUserID(string|int $userId){

        try {            
            return $this->applicationRepository->getApplicationsByUserID($userId);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível exibir a lista de candidaturas'
            ], 500);
        } 
    }


    public function create(Request $request){

        try {
            if($job = Job::find($request['job_id'])->status === StatusJob::OPEN){

                $this->applicationRepository->create($request);

                return response()->json([
                    'message' => 'Candidatura efetuada com sucesso'
                ], 200);
            }

            return response()->json([
                    'message' => 'Esta vaga não aceita mais candidaturas',
                ], 400);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível efetuar a candidatura'
            ], 500);
        } 
    }


    public function updateStatus(string|int $id, Request $request){
        
        try {
            if($application = Application::find($id)){

                $this->applicationRepository->updateStatus($application, $request);

                return response()->json([
                    'message' => 'Candidatura atualizada com sucesso'
                ], 200);
            }
            
            return response()->json([
                'message' => 'Candidatura não encontrada'
            ], 404);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível atualizar a candidatura'
            ], 500);
        }
    }


    public function delete(string|int $id){

        try {
            if($application = Application::find($id)){

                $this->applicationRepository->delete($application);

                return response()->json([
                    'message' => 'Candidatura removida com sucesso'
                ], 200);
            }
            
            return response()->json([
                'message' => 'Candidatura não encontrada'
            ], 404);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível remover a candidatura'
            ], 500);
        } 
    }

    public function bulkDelete(Request $request){

        try {
            $this->applicationRepository->bulkDelete($request);

            return response()->json([
                'message' => 'Candidaturas removidas com sucesso'
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'message' => 'Não foi possível remover as candidaturas',
                'error' => $th->getMessage()
            ], 500);
        } 
    }
}
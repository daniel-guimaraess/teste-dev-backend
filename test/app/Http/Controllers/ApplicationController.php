<?php

namespace App\Http\Controllers;

use App\Services\ApplicationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends Controller
{   
    private $applicationService;

    public function __construct(ApplicationService $applicationService)
    {
        $this->applicationService = $applicationService;
    }


    public function getApplicationsByJobID(string|int $jobId){

        return $this->applicationService->getApplicationsByJobID($jobId);
    }


    public function create(Request $request){

        Validator::make($request->all(), [
            'user_id' => 'required', 'integer',
            'job_id' => 'required', 'integer',

        ])->validate();

        return $this->applicationService->create($request);
    }


    public function updateStatus(string|int $id, Request $request){
        
        Validator::make($request->all(), [
            'status' => 'required', 'string'

        ])->validate();

        return $this->applicationService->updateStatus($id, $request);
    }


    public function delete(string|int $id){

        return $this->applicationService->delete($id);
    }
}
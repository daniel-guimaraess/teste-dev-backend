<?php

namespace App\Http\Controllers;

use App\Enums\TypeContract;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;

class JobController extends Controller
{   
    private $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }


    public function index(Request $request){

        return $this->jobService->index($request);
    }


    public function show(string|int $id){

        return $this->jobService->show($id);
    }


    public function create(Request $request){

        Validator::make($request->all(), [
            'name' => 'required', 'string', 'max:255',
            'description' => 'required', 'string',
            'requirements' => 'required', 'string',
            'type_contract' => 'required', new Enum(TypeContract::class),

        ])->validate();

        return $this->jobService->create($request);
    }


    public function update(string|int $id, Request $request){
        
        return $this->jobService->update($id, $request);
    }


    public function delete(string|int $id){

        return $this->jobService->delete($id);
    }


    public function finishJob(string|int $id){

        return $this->jobService->finishJob($id);
    }


    public function pauseJob(string|int $id){

        return $this->jobService->pauseJob($id);
    }

    public function bulkDelete(Request $request){

        Validator::make($request->all(), [
            'ids' => 'required', 'array',

        ])->validate();

        return $this->jobService->bulkDelete($request);
    }
}
<?php

namespace App\Repositories;

use App\Models\Job;
use Illuminate\Http\Request;

class JobRepository
{   
    public function index(Request $request){
        
        return Job::all();
    }

    public function show(string|int $id){

        return Job::find($id);
    }

    public function create(Request $request){
 
        Job::create([
            'name' => $request['name'],
            'description' => $request['description'],
            'requirements' => $request['requirements'],
            'type_contract' => $request['type_contract']
        ]);
    }

    public function update(Job $job, Request $request){

        $job->update([
            'name' => $request['name'],
            'description' => $request['description'],
            'requirements' => $request['requirements'],
            'type_contract' => $request['type_contract'],
            'status' => $request['status']
        ]);
    }

    public function delete(Job $job){

        $job->delete();
    }
    

    public function finishJob(Job $job){

        $job->update([
            'status' => 'finished'
        ]);
    }


    public function pauseJob(Job $job){

        $job->update([
            'status' => 'paused'
        ]);
    }
}
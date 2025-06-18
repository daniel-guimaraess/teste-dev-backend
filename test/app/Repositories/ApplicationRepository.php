<?php

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationRepository
{   
    public function getApplicationsByJobID($jobId){
        
        return Application::where('job_id', $jobId)->get();
    }

    public function getApplicationsByUserID($userId){
        
        return Application::where('user_id', $userId)->get();
    }

    public function create(Request $request){
 
        Application::create([
            'job_id' => $request['job_id'],
            'user_id' => $request['user_id'],
            'applied_at' => now(),
        ]);
    }

    public function updateStatus(Application $application, Request $request){

        $application->update([
            'status' => $request['status']
        ]);
    }

    public function delete(Application $application){

        $application->delete();
    }
}
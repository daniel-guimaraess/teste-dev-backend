<?php

namespace App\Repositories;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicationRepository
{   
    public function getApplicationsByJobID($jobId){
        
        $cacheKey = 'get_applications_job_' . $jobId;
        if(!Cache::has($cacheKey))
        {
            $result = Application::where('job_id', $jobId)->get();

            Cache::put($cacheKey, $result, 60);
        }

        return Cache::get($cacheKey);
    }

    public function getApplicationsByUserID($userId){
        
        $cacheKey = 'get_applications_user_' . $userId;
        if(!Cache::has($cacheKey))
        {
            $result = Application::where('user_id', $userId)->get();
            
            Cache::put($cacheKey, $result, 60);
        }

        return Cache::get($cacheKey);
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
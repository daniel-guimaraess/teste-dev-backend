<?php

namespace App\Repositories;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class JobRepository
{   
    public function index(Request $request){

        $filter = $request->get('filter');
        $paginateBy = $request->get('paginate');
        $orderBy = $request->get('order_by', 'id');
        $orderDir = $request->get('order_dir', 'desc');

        $cacheKey = 'users:index:' . md5(json_encode([
            'filter'     => $filter,
            'paginate'   => $paginateBy,
            'order_by'   => $orderBy,
            'order_dir'  => $orderDir,
        ]));

        if(!Cache::has($cacheKey)){

            $query = Job::query();

            if ($filter) {
                $columns = Schema::getColumnListing('job_vacancies');

                $query->where(function ($q) use ($columns, $filter) {
                    foreach ($columns as $column) {
                        $q->orWhere($column, 'like', '%' . $filter . '%');
                    }
                });
            }

            if (Schema::hasColumn('job_vacancies', $orderBy)) {
                $query->orderBy($orderBy, $orderDir);
            }

            $result = $paginateBy ? $query->paginate($paginateBy) : $query->paginate(20);

            Cache::put($cacheKey, $result, 60);
        }
        
        return Cache::get($cacheKey);
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
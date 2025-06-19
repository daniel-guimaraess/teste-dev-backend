<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class UserRepository
{   
    public function index(Request $request)
    {
        $filter     = $request->get('filter');
        $paginateBy = $request->get('paginate');
        $orderBy    = $request->get('order_by', 'id');
        $orderDir   = $request->get('order_dir', 'desc');

        $cacheKey = 'users:index:' . md5(json_encode([
            'filter'     => $filter,
            'paginate'   => $paginateBy,
            'order_by'   => $orderBy,
            'order_dir'  => $orderDir,
        ]));

        if (!Cache::has($cacheKey)) {
            $query = User::query();

            if ($filter) {
                $columns = Schema::getColumnListing('users');

                $query->where(function ($q) use ($columns, $filter) {
                    foreach ($columns as $column) {
                        $q->orWhere($column, 'like', '%' . $filter . '%');
                    }
                });
            }

            if (Schema::hasColumn('users', $orderBy)) {
                $query->orderBy($orderBy, $orderDir);
            }

            $result = $paginateBy ? $query->paginate($paginateBy) : $query->paginate(20);

            Cache::put($cacheKey, $result, 60);
        }

        return Cache::get($cacheKey);
    }

    public function show(string|int $id){

        return User::find($id);
    }

    public function create(Request $request){
 
        User::create([
            'role_id' => $request['role_id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password']
        ]);
    }

    public function update(User $user, Request $request){

        $user->update([
            'role_id' => $request['role_id'],
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password']
        ]);
    }

    public function delete(User $user){

        $user->delete();
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        User::whereIn('id', $ids)->each(function ($user) {
            $user->applications()->detach();
            $user->delete();
        });

        User::whereIn('id', $ids)->delete();
    }
}
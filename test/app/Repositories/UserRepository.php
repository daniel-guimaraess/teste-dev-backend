<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class UserRepository
{   
    public function index(Request $request){

        $query = User::query();

        $filter = $request->get('filter');
        $paginateBy = $request->get('paginate');
        $orderBy = $request->get('order_by', 'id');
        $orderDir = $request->get('order_dir', 'desc');

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

        return $paginateBy ? $query->paginate($paginateBy) : $query->paginate(20);
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
}
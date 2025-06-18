<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{   
    Use SoftDeletes;

    protected $fillable = [
        'job_id',
        'user_id',
        'status',
        'applied_at'        
    ];

    protected $appends = ['job_name', 'candidate_name'];

    protected function jobName(): Attribute
    {
        return Attribute::make(
        
        get: fn (mixed $value, array $attributes) => Job::where('id', $attributes['job_id'])->value('name')
        );   
    }

    protected function candidateName(): Attribute
    {
        return Attribute::make(
        
        get: fn (mixed $value, array $attributes) => User::where('id', $attributes['user_id'])->value('name')
        );   
    }
}

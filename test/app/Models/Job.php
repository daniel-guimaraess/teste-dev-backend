<?php

namespace App\Models;

use App\Enums\TypeContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    Use SoftDeletes;
    
    protected $table = 'job_vacancies';
    
    protected $fillable = [
        'name',
        'description',
        'requirements',
        'type_contract',
        'status'
    ];

    protected $casts = [
        'type_contract' => TypeContract::class,
    ];

     public function candidates(){
        
        return $this->belongsToMany(Application::class);
    }
}

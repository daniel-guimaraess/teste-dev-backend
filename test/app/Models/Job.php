<?php

namespace App\Models;

use App\Enums\StatusJob;
use App\Enums\TypeContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    Use SoftDeletes, HasFactory;
    
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
        'status' => StatusJob::class,
    ];

     public function applications(){
        
        return $this->belongsToMany(Application::class, 'applications');
    }
}

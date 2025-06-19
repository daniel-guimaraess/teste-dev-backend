<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportDataCSV extends Model
{
    protected $table = 'import_data_csv';

    protected $fillable = [
        'average',
        'median',
        'min_value',
        'max_value',
        'percent_above_10',
        'percent_below_minus_10',
        'percent_between_minus_10_and_10',
        'date',
    ];

    protected $dates = ['date'];
}

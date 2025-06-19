<?php

use App\Jobs\ImportDataCSV;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('import-data-csv', function () {
    
    ImportDataCSV::dispatch('example.csv');
    
})->purpose('Importa arquivo CSV');
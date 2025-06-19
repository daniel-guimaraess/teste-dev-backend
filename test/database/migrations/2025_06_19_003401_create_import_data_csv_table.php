<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('import_data_csv', function (Blueprint $table) {
            $table->id();
            $table->float('average')->nullable();
            $table->float('median')->nullable();
            $table->float('min_value')->nullable();
            $table->float('max_value')->nullable();
            $table->float('percent_above_10')->nullable();
            $table->float('percent_below_minus_10')->nullable();
            $table->float('percent_between_minus_10_and_10')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('import_data_csv');
    }
};

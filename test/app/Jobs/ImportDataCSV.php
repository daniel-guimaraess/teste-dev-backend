<?php

namespace App\Jobs;

use App\Models\ImportDataCSV as ModelsImportDataCSV;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Bus\Dispatchable;

class ImportDataCSV implements ShouldQueue
{
    use Queueable, Dispatchable;

    protected $csvPath;
    /**
     * Create a new job instance.
     */
    public function __construct(string $csvPath)
    {
        $this->csvPath = $csvPath;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $path = resource_path($this->csvPath);

        if (!file_exists($path)) {
            Log::error("Arquivo CSV não encontrado: {$path}");
            return;
        }

        $dates = [];

        if (($handle = fopen($path, 'r')) !== false) {
            $headers = fgetcsv($handle);

            while (($row = fgetcsv($handle)) !== false) {
                $dates[] = array_combine($headers, $row);
            }

            fclose($handle);
        }

        if ($dates) {
            $groupedData = [];
            foreach ($dates as $date) {
                $onlyDate = Carbon::parse($date['data'])->toDateString();
                
                if (!isset($groupedData[$onlyDate])) {
                    $groupedData[$onlyDate] = [];
                }

                $groupedData[$onlyDate][] = floatval($date['temperatura']);
            }

            foreach ($groupedData as $date => $temperatures) {
                
                $count = count($temperatures);
                $average = array_sum($temperatures) / $count;
                
                $min = min($temperatures);
                $max = max($temperatures);

                ModelsImportDataCSV::create(
                    [
                        'average' => $average,
                        'median' => $this->median($temperatures),
                        'min_value' => $min,
                        'max_value' => $max,
                        'percent_above_10' => $this->percentAboveTen($temperatures),
                        'percent_below_minus_10' => $this->percentBelowLessTen($temperatures),
                        'percent_between_minus_10_and_10' => $this->percentBetweenLessTenAndTen($temperatures),
                        'date' => $date
                    ]
                );
            }
        }
    }

    private function median($temperatures)
    {
        $count = count($temperatures);
        if ($count === 0) return 0;

        sort($temperatures);

        $middle = floor($count / 2);

        if ($count % 2 !== 0) {
            return $temperatures[$middle];
        }

        return ($temperatures[$middle - 1] + $temperatures[$middle]) / 2;
    }

    private function percentAboveTen($temperatures){

        $totalTemperatures = count($temperatures);
        $aboveTen = [];

        foreach($temperatures as $temperature){
            if($temperature > 10){
                $aboveTen[] = $temperature;
            }
        }

        return (count($aboveTen) / $totalTemperatures) * 100;
    }

    private function percentBelowLessTen($temperatures){

        $totalTemperatures = count($temperatures);
        $belowLessTen = [];

        foreach($temperatures as $temperature){
            if($temperature < -10){
                $belowLessTen[] = $temperature;
            }
        }

        return (count($belowLessTen) / $totalTemperatures) * 100;
    }

    private function percentBetweenLessTenAndTen($temperatures){

        $totalTemperatures = count($temperatures);
        $betweenLessTenAndTen = [];

        foreach($temperatures as $temperature){
            if ($temperature >= -10 && $temperature <= 10) {
                $betweenLessTenAndTen[] = $temperature;
            }
        }

        return (count($betweenLessTenAndTen) / $totalTemperatures) * 100;
    }
}

    <?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\BeatController;
use App\Models\Company;
// Artisan::command('app:daily-beats')->everyTwentySeconds();
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('app:daily-beats', function () {
    \Log::info('Beat was run at ' . now());
    $companies = Company::all();
    //$output = new \Symfony\Component\Console\Output\ConsoleOutput();
    $this->info('Beats tomorrow generated.!');
    //$output->writeln("hello");


    /**
     * Patrol
     * 1 For Recruit
     * 4 for PST
     */
    $beatController = new BeatController();
    foreach ($companies as $company) {
        $this->info($company->name);
        foreach ($company->areas as $area) {
            // 18:00 -> 00:00
            $this->info('Student company');
            $beatController->store(1, $area->id,NULL,  1, $company->id, "18", "00");
            //$this->info('PST company');
            //$beatController->store(4, $area->id, NULL,1, $company->id, "18", "00");
            //00:00 -> 6:00
            $this->info('Student company');
            $beatController->store(1, $area->id, NULL,1,  $company->id, "00", "6");
            //$this->info('PST company');
            //$beatController->store(4, $area->id, NULL,1,  $company->id, "00", "6");
        }
        foreach($company->patrol_areas as $patrol_area){
            //18:00 -> 00:00
            $beatController->store(1,NULL,$patrol_area->id,2,$company->id,"18","00");
            //$beatController->store(4,NULL,$patrol_area->id,2,$company->id,"18","00");
            //00:00 -> 6:00
            $beatController->store(1,NULL,$patrol_area->id,2,$company->id,"00","6");
            //$beatController->store(4,NULL,$patrol_area->id,2,$company->id,"00","6");
        }
    }
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();

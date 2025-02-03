<?php

namespace App\Console\Commands;
use App\Models\Company;

use App\Http\Controllers\BeatController;
use Illuminate\Console\Command;

class DailyBeats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:daily-beats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $companies = Company::all();
        foreach ($companies as $company) {
            foreach($company->areas as $area){
                $beatController = new BeatController();
                // 18:00 -> 00:00
                $beatController->store($area->id,1,$company->id,"18","00");
                //00:00 -> 6:00
                $beatController->store($area->id,1,$company->id,"00","6");
            }
        }
    }
}

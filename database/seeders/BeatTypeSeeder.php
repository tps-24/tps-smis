<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BeatType;
class BeatTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BeatType::create([
            'name' => "Guards",
            'description' =>""
        ]);

        BeatType::create([
<<<<<<< HEAD
            'name' => "Patrols",
=======
            'name' => "Patrol",
>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9
            'description' =>""
        ]);
    }
}

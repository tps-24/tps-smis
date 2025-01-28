<?php

namespace Database\Seeders;
use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Area::create([
            "name"=> "Kanisa la Roma","added_by"=>"1"
        ]);

        Area::create([
            "name"=> "Geti kuu","added_by"=>"1"
        ]);

        Area::create([
            "name"=> "Ofisi kuu","added_by"=>"1"
        ]);

        Area::create([
            "name"=> "Ukumbi wa mikutano","added_by"=>"1"
        ]);
    }
}

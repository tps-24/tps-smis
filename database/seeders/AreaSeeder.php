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
            "name"=> "Kanisa la Roma","added_by"=>"1","company_id"=>1,"campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "Geti kuu","added_by"=>"1","company_id"=>2,"campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "Ofisi kuu","added_by"=>"1","company_id"=>3,"campus_id"=> 1,
        ]);

        Area::create([
            "name"=> "Ukumbi wa mikutano","added_by"=>"1","company_id"=>4,"campus_id"=> 1,
        ]);
    }
}

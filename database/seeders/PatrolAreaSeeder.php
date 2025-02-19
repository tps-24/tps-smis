<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PatrolArea;
class PatrolAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PatrolArea::create([
            "added_by"=>"1",
            "campus_id"=> 1,
            "company_id" => 1,
            "start_area"=> "ASSEMBLY HALL",
            "end_area"=> "TOP GATE",
            "number_of_guards" => 4,
        ]);

        PatrolArea::create([
            "company_id" => 1,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ULINZI MKUU",
            "end_area"=> "LOWER GATE",
            "number_of_guards" => 4
        ]);

        PatrolArea::create([
            "company_id" => 1,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "CANTEEN",
            "end_area"=> "TANK LA MAJI",
            "number_of_guards" => 4
        ]);

        PatrolArea::create([
            "company_id" => 1,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "LOWER GATE",
            "end_area"=> "SOA HOUSE",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 1,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ASSEMBLY HALL",
            "end_area"=> "BWALO No.3",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 1,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "GETI LA SHULE",
            "end_area"=> "BWALO No.2",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 2,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "SOA HOUSE",
            "end_area"=> "BWALO No.2",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 2,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ASSEMBLY HALL",
            "end_area"=> "SOA HOUSE",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 2,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "STAFF GATE",
            "end_area"=> "CANTEEN",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 2,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "MABWENI YA WRC's",
            "end_area"=> "KISIMA CHA C-COY",
            "number_of_guards" => 3
        ]);
        
        PatrolArea::create([
            "company_id" => 3,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ASSEMBLY HALL",
            "end_area"=> "FARM",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 3,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "BARRIER CHOO CHA A-COY",
            "end_area"=> "TANK LA MAJI",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 3,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "LOWER GATE",
            "end_area"=> "RSM HOUSE",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 3,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ASSEMBLY HALL",
            "end_area"=> "SOA HOUSE",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 3,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "CHOO CHA WALIMU MESS",
            "end_area"=> "NYAMBIZI YA C-COY",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 4,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "FARM",
            "end_area"=> "A-COY",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 4,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ULINZI MKUU",
            "end_area"=> "LOWER GATE",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 4,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "MPS",
            "end_area"=> "CANTEEN",
            "number_of_guards" => 4
        ]);
        
        PatrolArea::create([
            "company_id" => 4,
            "added_by"=>"1",
            "campus_id"=> 1,
            "start_area"=> "ATM",
            "end_area"=> "MATANGA HOUSE",
            "number_of_guards" => 4
        ]);

    }
}

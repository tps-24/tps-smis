<?php

namespace Database\Seeders;
use App\Models\cOMPANY;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name'=>'HQ'
        ]);
        Company::create([
            'name'=>'A'
        ]);
        Company::create([
            'name'=>'B'
        ]);
        Company::create([
            'name'=>'C'
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create ([
            'user_id',
            'first_name',
            'middle_name',
            'last_name',
            'gender',
            'phone',
            'nin',
            'dob',
            'home_region',
            'company',
            'education_level',
            'height',
            'weight',
        ]);
    }
}

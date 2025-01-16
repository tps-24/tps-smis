<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GradeMappingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('grade_mappings')->insert([
            ['grading_system_id' => 1, 'grade' => 'A', 'grade_point' => '4', 'min_score' => 80, 'max_score' => 100,'remarks' => 'Excellent'],
            ['grading_system_id' => 1, 'grade' => 'B', 'grade_point' => '3','min_score' => 65, 'max_score' => 79, 'remarks' => 'Good'],
            ['grading_system_id' => 1, 'grade' => 'C', 'grade_point' => '2','min_score' => 50, 'max_score' => 64, 'remarks' => 'Pass'],
            ['grading_system_id' => 1, 'grade' => 'D', 'grade_point' => '1','min_score' => 40, 'max_score' => 49, 'remarks' => 'Poor'],
            ['grading_system_id' => 1, 'grade' => 'F', 'grade_point' => '0','min_score' => 0, 'max_score' => 39, 'remarks' => 'Failure'],
            ['grading_system_id' => 1, 'grade' => 'I', 'grade_point' => '0','min_score' => 0, 'max_score' => 0, 'remarks' => 'Incomplete'],
            ['grading_system_id' => 1, 'grade' => 'Q', 'grade_point' => '0','min_score' => 0, 'max_score' => 0, 'remarks' => 'Disqualification'],
            ['grading_system_id' => 2, 'grade' => 'A', 'grade_point' => '4', 'min_score' => 75, 'max_score' => 100,'remarks' => 'Excellent'],
            ['grading_system_id' => 2, 'grade' => 'B+', 'grade_point' => '3','min_score' => 65, 'max_score' => 74, 'remarks' => 'Very Good'],
            ['grading_system_id' => 2, 'grade' => 'B', 'grade_point' => '2','min_score' => 55, 'max_score' => 64, 'remarks' => 'Good'],
            ['grading_system_id' => 2, 'grade' => 'C', 'grade_point' => '1','min_score' => 45, 'max_score' => 54, 'remarks' => 'Average'],
            ['grading_system_id' => 2, 'grade' => 'D', 'grade_point' => '0','min_score' => 35, 'max_score' => 44, 'remarks' => 'Poor'],
            ['grading_system_id' => 2, 'grade' => 'F', 'grade_point' => '0','min_score' => 0, 'max_score' => 34, 'remarks' => 'Failure'],
            ['grading_system_id' => 2, 'grade' => 'I', 'grade_point' => '0','min_score' => 0, 'max_score' => 0, 'remarks' => 'Incomplete'],
            ['grading_system_id' => 2, 'grade' => 'Q', 'grade_point' => '0','min_score' => 0, 'max_score' => 0, 'remarks' => 'Disqualification'],
            ['grading_system_id' => 3, 'grade' => 'A', 'grade_point' => '4','min_score' => 3.5, 'max_score' => 4, 'remarks' => 'First Class'],
            ['grading_system_id' => 3, 'grade' => 'B', 'grade_point' => '3','min_score' => 3.0, 'max_score' => 3.4, 'remarks' => 'Second Class'],
            ['grading_system_id' => 3, 'grade' => 'C', 'grade_point' => '2','min_score' => 2.0, 'max_score' => 2.9, 'remarks' => 'Pass'],
            ['grading_system_id' => 3, 'grade' => 'D', 'grade_point' => '1','min_score' => 1.0, 'max_score' => 1.9, 'remarks' => 'Poor'],
            ['grading_system_id' => 3, 'grade' => 'F', 'grade_point' => '0','min_score' => 0.0, 'max_score' => 0.9, 'remarks' => 'Failure'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimetableSeeder extends Seeder
{
    public function run()
    {
        DB::table('timetables')->insert([
            [
                'company' => 'A',
                'day' => 'Monday',
                'venue' => 'Room 101',
                'subject' => 'ICT',
                'teacher' => 'neema john',
                'start_time' => '08:00:00',
                'end_time' => '10:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more records as needed
        ]);
    }
}

<?php

namespace Database\Seeders;
<<<<<<< HEAD

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
=======
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Activity;
use App\Models\Venue;
use App\Models\Instructor;
>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9

class TimetableSeeder extends Seeder
{
    public function run()
    {
<<<<<<< HEAD
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
=======
        // Create sample activities
        $activities = ['Criminal Law', 'Police duties', 'Drill', 'Criminal Procedure', 'Communication skills'];
        foreach ($activities as $activity) {
            Activity::firstOrCreate(['name' => $activity]);
        }

        // Create sample venues
        $venues = ['Assembly hall', 'Uwanja wa Damu', 'Mess B- COY', 'ABC 102', 'ABC 104'];
        foreach ($venues as $venue) {
            Venue::firstOrCreate(['name' => $venue]);
        }

        // Create sample instructors
        $instructors = ['INSP. MAPUNDA', 'BALTAZARY', 'Dr. SAYUNI', 'SGT.MHINA', 'Prof. ADAM'];
        foreach ($instructors as $instructor) {
            Instructor::firstOrCreate(['name' => $instructor]);
        }
>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9
    }
}

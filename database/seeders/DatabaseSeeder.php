<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
<<<<<<< HEAD

        $this->call([
            // PermissionTableSeeder::class,
            // CreateAdminUserSeeder::class,
            // BeatTypeSeeder::class,
            // BeatExceptionSeeder::class,
            // BeatTimeExceptionSeeder::class,
            // GuardAreaSeeder::class,
            // PatrolAreaSeeder::class,
            // VitengoSeeder::class,
            // CompanySeeder::class,
            // PlatoonSeeder::class,
            // AttendenceTypeSeeder::class,
            // AttendenceSeeder::class,
            // GradingSystemsTableSeeder::class,
            // PatientsTableSeeder::class,
=======
        

        $this->call([
            PermissionTableSeeder::class,
            CreateAdminUserSeeder::class,
            BeatTypeSeeder::class,
            AreaSeeder::class,
            VitengoSeeder::class,
            CompanySeeder::class,
            PlatoonSeeder::class,
            AttendenceTypeSeeder::class,
            AttendenceSeeder::class,
            GradingSystemsTableSeeder::class,
            PatientsTableSeeder::class,
>>>>>>> 7d61e4df868b37df109c9a8e92bdee3250c6fbd9
        ]);

        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}



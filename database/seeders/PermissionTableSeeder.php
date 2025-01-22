<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $permissions = [ 
            ['name' => 'role-list', 'description' => 'View roles'], 
            ['name' => 'role-create', 'description' => 'Create new roles'], 
            ['name' => 'role-edit', 'description' => 'Edit existing roles'], 
            ['name' => 'role-delete', 'description' => 'Delete roles'], 
            ['name' => 'product-list', 'description' => 'View products'], 
            ['name' => 'product-create', 'description' => 'Create new products'], 
            ['name' => 'product-edit', 'description' => 'Edit existing products'], 
            ['name' => 'product-delete', 'description' => 'Delete products'], 
            ['name' => 'user-list', 'description' => 'View users'], 
            ['name' => 'user-create', 'description' => 'Create new users'], 
            ['name' => 'user-edit', 'description' => 'Edit existing users'], 
            ['name' => 'user-delete', 'description' => 'Delete users'], 
            ['name' => 'student-list', 'description' => 'View students'], 
            ['name' => 'student-create', 'description' => 'Create new students'], 
            ['name' => 'student-edit', 'description' => 'Edit existing students'], 
            ['name' => 'student-delete', 'description' => 'Delete students'],
            ['name' => 'staff-list', 'description' => 'View staffs'], 
            ['name' => 'staff-create', 'description' => 'Create new staffs'], 
            ['name' => 'staff-edit', 'description' => 'Edit existing staffs'], 
            ['name' => 'staff-delete', 'description' => 'Delete staffs'], 
            ['name' => 'profile-list', 'description' => 'View profile'], 
            ['name' => 'profile-create', 'description' => 'Create new profile'], 
            ['name' => 'profile-edit', 'description' => 'Edit existing profile'], 
            ['name' => 'profile-delete', 'description' => 'Delete profile'],
            ['name' => 'attendance-list', 'description' => 'View attendance'], 
            ['name' => 'attendance-create', 'description' => 'Create new attendance'], 
            ['name' => 'attendance-edit', 'description' => 'Edit existing attendance'], 
            ['name' => 'attendance-delete', 'description' => 'Delete attendance'], 
            ['name' => 'department-list', 'description' => 'View departments'], 
            ['name' => 'department-create', 'description' => 'Create new departments'], 
            ['name' => 'department-edit', 'description' => 'Edit existing departments'], 
            ['name' => 'department-delete', 'description' => 'Delete departments'],
            ['name' => 'programme-list', 'description' => 'View programmes'], 
            ['name' => 'programme-create', 'description' => 'Create new programmes'], 
            ['name' => 'programme-edit', 'description' => 'Edit existing programmes'], 
            ['name' => 'programme-delete', 'description' => 'Delete programmes'], 
            ['name' => 'course-list', 'description' => 'View courses'], 
            ['name' => 'course-create', 'description' => 'Create new courses'], 
            ['name' => 'course-edit', 'description' => 'Edit existing courses'], 
            ['name' => 'course-delete', 'description' => 'Delete courses'],
            ['name' => 'coursework-list', 'description' => 'View courseworks'], 
            ['name' => 'coursework-create', 'description' => 'Create new courseworks'], 
            ['name' => 'coursework-edit', 'description' => 'Edit existing courseworks'], 
            ['name' => 'coursework-delete', 'description' => 'Delete courseworks'], 
            ['name' => 'semester-exam-list', 'description' => 'View semester examination'], 
            ['name' => 'semester-exam-create', 'description' => 'Create new semester examination'], 
            ['name' => 'semester-exam-edit', 'description' => 'Edit existing semester examination'], 
            ['name' => 'semester-exam-delete', 'description' => 'Delete semester examination'],  
            ['name' => 'print-certificate', 'description' => 'Print certificate'],
            ['name' => 'announcement-list', 'description' => 'View announcements'], 
            ['name' => 'announcement-create', 'description' => 'Create new announcements'], 
            ['name' => 'announcement-edit', 'description' => 'Edit existing announcements'], 
            ['name' => 'announcement-delete', 'description' => 'Delete announcements'], 
            ['name' => 'download-list', 'description' => 'View downloads'], 
            ['name' => 'download-create', 'description' => 'Create new downloads'], 
            ['name' => 'download-edit', 'description' => 'Edit existing downloads'], 
            ['name' => 'download-delete', 'description' => 'Delete downloads'],
            ['name' => 'hospital-list', 'description' => 'View sick students'], 
            ['name' => 'hospital-create', 'description' => 'Create new sick students'], 
            ['name' => 'hospital-edit', 'description' => 'Edit existing sick students'], 
            ['name' => 'hospital-delete', 'description' => 'Delete sick students'], 
            ['name' => 'mps-list', 'description' => 'View products'], 
            ['name' => 'mps-create', 'description' => 'Create new products'], 
            ['name' => 'mps-edit', 'description' => 'Edit existing products'], 
            ['name' => 'mps-delete', 'description' => 'Delete products'],
            ['name' => 'leave-list', 'description' => 'View leave'], 
            ['name' => 'leave-create', 'description' => 'Create new leave'], 
            ['name' => 'leave-edit', 'description' => 'Edit existing leave'], 
            ['name' => 'leave-delete', 'description' => 'Delete leave'], 
            ['name' => 'beat-list', 'description' => 'View beats'], 
            ['name' => 'beat-create', 'description' => 'Create new beats'], 
            ['name' => 'beat-edit', 'description' => 'Edit existing beats'], 
            ['name' => 'beat-delete', 'description' => 'Delete beats'], 
            ['name' => 'setting-list', 'description' => 'View settings'], 
            ['name' => 'setting-create', 'description' => 'Create new settings'], 
            ['name' => 'setting-edit', 'description' => 'Edit existing settings'], 
            ['name' => 'setting-delete', 'description' => 'Delete settings'], 
            ['name' => 'programme-session-list', 'description' => 'View session programme'], 
            ['name' => 'programme-session-create', 'description' => 'Create new session programme'], 
            ['name' => 'programme-session-edit', 'description' => 'Edit existing session programme'], 
            ['name' => 'programme-session-delete', 'description' => 'Delete session programme'], 
            ['name' => 'report-list', 'description' => 'View reports'], 
            ['name' => 'report-create', 'description' => 'Create new reports'], 
            ['name' => 'report-edit', 'description' => 'Edit existing reports'], 
            ['name' => 'report-delete', 'description' => 'Delete reports'],        
            ['name' => 'enroll-students', 'description' => 'Enroll students to courses'],        
            ['name' => 'generate-results', 'description' => 'Generate results Overview'],        
            // Add other permissions here with descriptions 
            ]; 

            foreach ($permissions as $permission) { 
                Permission::updateOrCreate( 
                    ['name' => $permission['name']], // Match by name 
                    ['description' => $permission['description']] // Update description 
                    ); 
            }
    }
}

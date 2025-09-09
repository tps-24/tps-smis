<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeaponType;
use App\Models\Category;

class WeaponTypesTableSeeder extends Seeder
{
    public function run(): void
    {
        // Optional: clear table first
        WeaponType::truncate();

        // Define types and their category names
        $weaponTypes = [
            ['name' => 'Rifle', 'description' => 'Standard military rifles', 'category_name' => 'Firearms'],
            ['name' => 'Pistol', 'description' => 'Handguns for close combat', 'category_name' => 'Firearms'],
            ['name' => 'Machine Gun', 'description' => 'Automatic firing weapons', 'category_name' => 'explosives'],
            ['name' => 'Shotgun', 'description' => 'Close-range firearms', 'category_name' => 'Firearms'],
            ['name' => 'Grenade', 'description' => 'Explosive devices', 'category_name' => 'Explosives'],
            ['name' => 'Rocket Launcher', 'description' => 'Anti-armor weapons', 'category_name' => 'Explosives'],
            ['name' => 'Sniper Rifle', 'description' => 'Long-range precision rifles', 'category_name' => 'Firearms'],
            ['name' => 'Sniper', 'description' => 'Long-range precision rifles', 'category_name' => 'Firearms'],
            ['name' => 'Ammunition', 'description' => 'Bullets and shells', 'category_name' => 'Ammunition'],
        ];

        foreach ($weaponTypes as $type) {
            $category = Category::where('name', $type['category_name'])->first();

            WeaponType::create([
                'name' => $type['name'],
                'description' => $type['description'],
                'category_id' => $category ? $category->id : null,
            ]);
        }
    }
}

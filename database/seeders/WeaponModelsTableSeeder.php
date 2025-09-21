<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeaponModel;
use App\Models\WeaponType;
use App\Models\Category;

class WeaponModelsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure categories exist
        $firearm   = Category::firstOrCreate(['name' => 'Firearm'], ['description' => 'Guns and related weapons']);
        $explosive = Category::firstOrCreate(['name' => 'Explosive'], ['description' => 'Grenades, mines, bombs']);
        $ammo      = Category::firstOrCreate(['name' => 'Ammunition'], ['description' => 'Bullets, shells, magazines']);

        // Ensure weapon types exist
        $rifle   = WeaponType::firstOrCreate(['name' => 'Rifle'], ['description' => 'Long guns like AK-47, M16']);
        $pistol  = WeaponType::firstOrCreate(['name' => 'Pistol'], ['description' => 'Handguns like Glock']);
        $smg     = WeaponType::firstOrCreate(['name' => 'SMG'], ['description' => 'Submachine guns']);
        $lmg     = WeaponType::firstOrCreate(['name' => 'LMG'], ['description' => 'Light machine guns']);
        $shotgun = WeaponType::firstOrCreate(['name' => 'Shotgun'], ['description' => 'Close range weapons']);

        // Define default models
        $models = [
            ['name' => 'AK-47',        'weapon_type_id' => $rifle->id,   'category_id' => $firearm->id],
            ['name' => 'M16',          'weapon_type_id' => $rifle->id,   'category_id' => $firearm->id],
            ['name' => 'Glock 17',     'weapon_type_id' => $pistol->id,  'category_id' => $firearm->id],
            ['name' => 'Beretta M9',   'weapon_type_id' => $pistol->id,  'category_id' => $firearm->id],
            ['name' => 'MP5',          'weapon_type_idd' => $smg->id,     'category_id' => $firearm->id],
            ['name' => 'Uzi',          'weapon_type_id' => $smg->id,     'category_id' => $firearm->id],
            ['name' => 'M249 SAW',     'weapon_type_id' => $lmg->id,     'category_id' => $firearm->id],
            ['name' => 'PKM',          'weapon_type_id' => $lmg->id,     'category_id' => $firearm->id],
            ['name' => 'Remington 870','weapon_type_id' => $shotgun->id, 'category_id' => $firearm->id],
            ['name' => 'Mossberg 500', 'weapon_type_id' => $shotgun->id, 'category_id' => $firearm->id],

            // Explosives
            ['name' => 'Hand Grenade', 'weapon_type_id' => null, 'category_id' => $explosive->id],
            ['name' => 'Claymore Mine','weapon_type_id' => null, 'category_id' => $explosive->id],
            ['name' => 'C4 Explosive', 'weapon_type_id' => null, 'category_id' => $explosive->id],

            // Ammunition
            ['name' => '7.62mm Rounds','weapon_type_id' => null, 'category_id' => $ammo->id],
            ['name' => '9mm Rounds',   'weapon_type_id' => null, 'category_id' => $ammo->id],
            ['name' => '5.56mm Rounds','weapon_type_id' => null, 'category_id' => $ammo->id],
        ];

        foreach ($models as $model) {
            WeaponModel::firstOrCreate(
                ['name' => $model['name']],
                [
                    'weapon_type_id'     => $model['weapon_type_id'],
                    'category_id' => $model['category_id'],
                ]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->insert([
            ['name' => 'Super Admin', 'description' => 'Full system access'],
            ['name' => 'Admin', 'description' => 'Administrative access'],
            ['name' => 'Supervisor', 'description' => 'Supervisory access'],
        ]);
    }
}

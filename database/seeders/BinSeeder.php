<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BinSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create wood bins (60kg capacity each)
        for ($i = 1; $i <= 10; $i++) {
            \App\Models\Bin::create([
                'bin_number' => 'WOOD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => 'wood',
                'weight_capacity' => 60.00,
                'current_weight' => 0,
                'status' => 'available',
                'notes' => 'Bin de madera para ciruelas',
            ]);
        }

        // Create plastic bins (45kg capacity each)
        for ($i = 1; $i <= 15; $i++) {
            \App\Models\Bin::create([
                'bin_number' => 'PLASTIC-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'type' => 'plastic',
                'weight_capacity' => 45.00,
                'current_weight' => 0,
                'status' => 'available',
                'notes' => 'Bin plástico para ciruelas',
            ]);
        }
    }
}

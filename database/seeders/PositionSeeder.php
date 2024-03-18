<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/position.json");
        $positions = json_decode($json);

        foreach ($positions as $position) {
            Position::create([
                "name" => $position->name
            ]);
        }
    }
}

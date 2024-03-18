<?php

namespace Database\Seeders;

use App\Models\Office;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/office.json");
        $offices = json_decode($json);

        foreach ($offices as $offices) {
            Office::create([
                "name"=> $offices->name,
            ]);
        }
    }
}

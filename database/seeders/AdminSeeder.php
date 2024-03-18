<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/admin.json");
        $admins = json_decode($json);

        foreach ($admins as $admin) {
            Admin::create([
                "name" => $admin->name,
                "username" => $admin->username,
                "password" => $admin->password
            ]);
        }
    }
}

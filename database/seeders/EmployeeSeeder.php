<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $json = File::get("database/data/employee.json");
        $employees = json_decode($json);

        foreach ($employees as $employee) {
            Employee::create([
                "full_name" => $employee->fullname,
                "username" => $employee->username,
                "password" => $employee->password,
                "position_id" => $employee->position_id,
                "office_id" => $employee->office_id
            ]);
        }
    }
}

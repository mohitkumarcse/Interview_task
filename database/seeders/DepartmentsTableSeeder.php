<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['department_id' => 1, 'department_name' => 'Software Developer'],
            ['department_id' => 2, 'department_name' => 'Graphic Designers'],
            ['department_id' => 3, 'department_name' => 'Finance'],
        ]);
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SchoolClass;

class SchoolClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SchoolClass::create(['grade_level' => 7]);
        SchoolClass::create(['grade_level' => 8]);
        SchoolClass::create(['grade_level' => 9]);
        SchoolClass::create(['grade_level' => 10]);
        SchoolClass::create(['grade_level' => 11]);
        SchoolClass::create(['grade_level' => 12]);
    }
}

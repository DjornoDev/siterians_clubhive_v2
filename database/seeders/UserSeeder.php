<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\Section;
use App\Models\SchoolClass;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get first section
        $section = Section::first();

        User::create([
            'role' => 'ADMIN',
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'section_id' => $section->section_id ?? null,
        ]);

        User::create([
            'role' => 'TEACHER',
            'name' => 'Teacher User',
            'email' => 'teacher@gmail.com',
            'password' => bcrypt('password'),
            'section_id' => $section->section_id ?? null,
        ]);

        User::create([
            'role' => 'STUDENT',
            'name' => 'Student User',
            'email' => 'student@gmail.com',
            'password' => bcrypt('password'),
            'section_id' => $section->section_id ?? null,
        ]);
    }
}

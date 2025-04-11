<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Section;
use App\Models\SchoolClass;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = SchoolClass::all();

        $sections = ['Alpha', 'Beta', 'Gamma'];

        foreach ($classes as $class) {
            foreach ($sections as $section) {
                Section::create([
                    'class_id' => $class->class_id,
                    'section_name' => $section
                ]);
            }
        }
    }
}

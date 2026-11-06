<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $user = User::first();
    Project::create([
        'user_id' => 1,
        'title' => 'Mi Primer Proyecto',
        'description' => 'Descripción del proyecto de prueba.',
        'due_date' => '2026-12-31',
        'status' => 'en_progreso',
    ]);
    }
}

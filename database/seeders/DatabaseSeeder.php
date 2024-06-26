<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Profil;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ProfilsSeeder::class,
        ]);
        // \App\Models\Admin::factory(10)->create();

        // \App\Models\Admin::factory()->create([
        //     'name' => 'Test Admin',
        //     'email' => 'test@example.com',
        // ]);
    }
}

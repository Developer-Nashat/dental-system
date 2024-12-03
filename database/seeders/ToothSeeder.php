<?php

namespace Database\Seeders;

use App\Models\Tooth;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ToothSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tooth::insert([
        //     ['']
        // ]);

        Tooth::factory()->count(32)->create();
    }
}

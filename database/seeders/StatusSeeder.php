<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Status::factory()->count(3)->sequence(
            ['name' => 'إنتظار', 'symbol' => 'P', 'color' => '#FF9800'],
            ['name' => 'ملغي', 'symbol' => 'C', 'color' => '#616161'],
            ['name' => 'أكتمل', 'symbol' => 'F', 'color' => '#4CAF50'],
            ['name' => 'جديد', 'symbol' => 'N', 'color' => '#03A9F4'],
        )->create();
    }
}

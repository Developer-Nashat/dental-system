<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tooth>
 */
class ToothFactory extends Factory
{
    private static $toothNumber = 1;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tooth_number' => static::$toothNumber++,
        ];
    }
}

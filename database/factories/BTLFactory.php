<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\BTL;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BTL>
 */
class BTLFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reg_no'=>'ARC-00002',
            'name'=>fake()->name,
            'cnic'=>'35201-1513206-1',
            'mobile'=>'056-2024497',
            'tell_no'=>'056-2024497',
            'project_type'=>'Residential',
            'phase'=>'Al Rehmat Housing',
            'plot_size'=>'4M',
            'sector'=>'B',
            'plot_no'=>'176'
        ];
    }
}

<?php

namespace Database\Factories;

use App\Models\Penjualan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PenjualanFactory extends Factory
{
    protected $model = Penjualan::class;

    public function definition()
    {
        return [
            'tanggal' => $this->faker->dateTimeThisYear(),
            'kasir_id' => \App\Models\User::factory(), // Assuming the kasir_id is related to User
            // Add other fields as necessary
        ];
    }
}

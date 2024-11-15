<?php

namespace Database\Seeders;

use App\Models\Penjualan;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'kasir',
            'email' => 'kasir@email.com',
            'password' => Hash::make('password'),
        ]);
        Penjualan::factory()->create([
            'tanggal' => now(),
            'kasir_id' => $user->id
        ]);
    }
}

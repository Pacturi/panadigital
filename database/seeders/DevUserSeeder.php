<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DevUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => 'dev@gmail.com'],
            [
                'name' => 'Dev',
                'password' => Hash::make('12345678'),
                'role' => 'dev',
                'tenant_id' => null,
            ],
        );
    }
}

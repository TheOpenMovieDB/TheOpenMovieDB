<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (null === config('system.password')) {
            throw new Exception('The system password cannot be null.');
        }
        User::create([
            'name' => config('system.name'),
            'email' => config('system.email'),
            'password' => Hash::make(config('system.password')),
            'email_verified_at' => now(),
        ]);
    }


}

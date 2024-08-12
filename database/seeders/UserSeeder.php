<?php

namespace Database\Seeders;

use App\Models\User;
use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (config('system.password') === null) {
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Roller & izinler
        $this->call(SuperAdminRoleSeeder::class);

        // 2) Kullanıcılar
        $user = User::firstOrCreate(
            ['email' => 'cakirhakki@gmail.com'],
            [
                'name' => 'Hakkı Çakır',
                'phone' => '5551112233',
                'password' => Hash::make('134679.'),
                'email_verified_at' => now(),
            ],
        );
        $user->assignRole('super_admin');

        if (app()->environment('local')) {
    \App\Models\Customer::query()->whereNull('email_verified_at')
        ->update(['email_verified_at' => now()]);
}
    }
}

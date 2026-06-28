<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');
        $now = Carbon::now();

        $mainUsers = [
            [
                'name' => 'Administrator',
                'email' => 'admin@qitchen.test',
                'role' => 'admin',
                'phone' => '081111111111',
                'referral_code' => null,
            ],
            [
                'name' => 'Cashier Qitchen',
                'email' => 'cashier@qitchen.test',
                'role' => 'cashier',
                'phone' => '082222222222',
                'referral_code' => null,
            ],
            [
                'name' => 'Kitchen Staff',
                'email' => 'kitchen@qitchen.test',
                'role' => 'kitchen',
                'phone' => '083333333333',
                'referral_code' => null,
            ],
            [
                'name' => 'Courier Qitchen',
                'email' => 'courier@qitchen.test',
                'role' => 'courier',
                'phone' => '084444444444',
                'referral_code' => null,
            ],
            [
                'name' => 'John Customer',
                'email' => 'customer@qitchen.test',
                'role' => 'customer',
                'phone' => '085555555555',
                'referral_code' => 'REFJOHN01',
            ]
        ];

        foreach ($mainUsers as $user) {
            User::create(array_merge($user, [
                'password' => $password,
                'email_verified_at' => $now,
                'remember_token' => Str::random(10),
                'created_at' => $now,
                'updated_at' => $now,
            ]));
        }
    }
}
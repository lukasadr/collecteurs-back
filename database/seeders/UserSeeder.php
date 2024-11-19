<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insert user
        // DB::table('users')->insert([
        //     'role_id' => 1,
        //     'nom' => 'Admin User',
        //     'adresse' => '123 Admin Street',
        //     'tel' => '1234567890',
        //     'societe' => 'Admin Corp',
        //     'notes' => 'This is an admin user.',
        //     'solde' => null,
        //     'type' => null,
        //     'nifstat' => null,
        //     'photo' => '/images/default.png',
        //     'email' => 'admin@example.com',
        //     'email_verified_at' => now(),
        //     'password' => Hash::make('password'),
        //     'remember_token' => \Illuminate\Support\Str::random(10),
        //     'created_at' => now(),
        //     'updated_at' => now(),
        // ]);

        User::factory(200)->create();
    }
}

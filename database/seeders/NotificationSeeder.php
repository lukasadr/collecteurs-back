<?php

namespace Database\Seeders;

use App\Models\User;
use App\Notifications\ValidationCommande;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role_id', 1)->first();

        for ($i=0; $i < 5; $i++) {
            $admin->notify(new ValidationCommande());
        }
    }
}

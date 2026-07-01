<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('AEM_ADMIN_EMAIL', 'admin@aem.test')],
            [
                'name' => env('AEM_ADMIN_NAME', 'Admin AEM'),
                'password' => Hash::make(env('AEM_ADMIN_PASSWORD', 'password')),
                'email_verified_at' => now(),
            ]
        );
    }
}

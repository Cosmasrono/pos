<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles and permissions first
        $this->call(RolePermissionSeeder::class);

        // Create default super admin
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $admin->roles()->attach(\App\Models\Role::where('name', 'super_admin')->first());

        // Create a test cashier
        $cashier = User::factory()->create([
            'name' => 'Test Cashier',
            'email' => 'cashier@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $cashier->roles()->attach(\App\Models\Role::where('name', 'cashier')->first());
    }
}

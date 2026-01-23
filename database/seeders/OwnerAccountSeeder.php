<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class OwnerAccountSeeder extends Seeder
{
    public function run(): void
    {
        $ownerRole = Role::where('name', 'owner')->first();

        $owner = User::firstOrCreate(
            ['email' => 'owner@wingpos.com'],
            [
                'name' => 'System Owner',
                'password' => Hash::make('Owner@2026!'),
                'phone' => '0700000000',
                'is_active' => true,
            ]
        );

        if ($ownerRole) {
            $owner->roles()->syncWithoutDetaching([$ownerRole->id]);
        }
    }
}

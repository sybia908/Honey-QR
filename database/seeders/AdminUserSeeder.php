<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'andikabgs@gmail.com',
            'username' => 'afrils',
            'password' => Hash::make('G4l4xymini'),
            'is_active' => true,
        ]);

        $admin->assignRole('admin');
    }
}

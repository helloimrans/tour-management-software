<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'email' => 'admin@tour.com',
            'phone' => '01700000001',
            'password' => Hash::make('password123'),
            'user_type' => User::ADMIN_USER_CODE,
            'status' => 1,
        ]);

        // Attach admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $admin->addRole($adminRole);
        }

        // Create Member User
        $member = User::create([
            'first_name' => 'Member',
            'last_name' => 'User',
            'email' => 'member@tour.com',
            'phone' => '01700000002',
            'password' => Hash::make('password123'),
            'user_type' => User::NORMAL_USER_CODE,
            'status' => 1,
        ]);

        // Attach member role
        $memberRole = Role::where('name', 'user')->first();
        if ($memberRole) {
            $member->addRole($memberRole);
        }

        $this->command->info('✅ Default users created successfully!');
        $this->command->info('📧 Admin: admin@tour.com | Password: password123');
        $this->command->info('📧 Member: member@tour.com | Password: password123');
    }
}


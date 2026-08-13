<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(
            ['slug' => 'super_admin'],
            [
                'name' => 'Super Admin',
                'permissions' => json_encode(['*' => true]),
            ]
        );

        $staff = Role::firstOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Staff',
                'permissions' => json_encode([
                    'products' => true,
                    'orders' => true,
                    'customers' => true,
                    'banners' => true,
                    'featured_products' => true,
                    'cms_pages' => true,
                    'stock' => true,
                    'reviews' => true,
                ]),
            ]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'role_id' => $superAdmin->id,
                'is_active' => true,
            ]
        );
    }
}

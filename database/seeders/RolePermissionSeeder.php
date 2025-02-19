<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage-users',

            'create-store',
            'edit-store',
            'delete-store',
            'view-store',

            'create-product',
            'edit-product',
            'delete-product',
            'view-product',

            'create-cart',
            'edit-cart',
            'delete-cart',
            'view-cart',

            'complete-purchase',
            'view-purchase-history',
        ];
        
        // Create permissions in the database
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        // Create roles and assign permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'manage-users',
            'create-store',
            'edit-store',
            'delete-store',
            'view-store',
            'create-product',
            'edit-product',
            'delete-product',
            'view-product',
            'create-cart',
            'edit-cart',
            'delete-cart',
            'view-cart',
            'complete-purchase',
            'view-purchase-history',
        ]);
        
        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $sellerRole->givePermissionTo([
            'create-store',
            'edit-store',
            'delete-store',
            'view-store',
            'create-product',
            'edit-product',
            'delete-product',
            'view-product',
            'view-purchase-history',
        ]);
        
        $customerRole = Role::firstOrCreate(['name' => 'customer']);
        $customerRole->givePermissionTo([
            'view-product',
            'create-cart',
            'edit-cart',
            'delete-cart',
            'view-cart',
            'complete-purchase',
            'view-purchase-history',
        ]);
    }
}

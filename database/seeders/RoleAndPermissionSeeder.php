<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          
                $admin = Role::create(['name' => 'admin']);
                $author = Role::create(['name' => 'author']);
        
            
                $createPost = Permission::create(['name' => 'create posts']);
                $editOwnPost = Permission::create(['name' => 'edit own posts']);
                $deleteOwnPost = Permission::create(['name' => 'delete own posts']);
        
               
                $admin->givePermissionTo(Permission::all());
        
        
                $author->givePermissionTo($createPost);
                $author->givePermissionTo($editOwnPost);
                $author->givePermissionTo($deleteOwnPost);
    }
}

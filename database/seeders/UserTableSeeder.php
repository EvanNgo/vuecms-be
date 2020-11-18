<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Role::truncate();
        Permission::truncate();
        $faker = \Faker\Factory::create();
        $password = Hash::make('123123');
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => $password,
        ]);

        Role::create([
            'name' => 'Thành viên',
            'slug' => 'member'
        ]);
        Role::create([
            'name' => 'Quản trị viên',
            'slug' => 'admin'
        ]);
        Role::create([
            'name' => 'Nhân viên',
            'slug' => 'staff'
        ]);


        DB::table('user_role')->insert([
            'role_id' => 2,
            'user_id' => 1,
        ]);

        Permission::create([
            'name' => 'Xem',
            'slug' => 'view_product'
        ]);
        Permission::create([
            'name' => 'Tạo sản phẩm',
            'slug' => 'editor_product'
        ]);
        Permission::create([
            'name' => 'Xoá',
            'slug' => 'delete_product'
        ]);
        Permission::create([
            'name' => 'Khôi phục',
            'slug' => 'restore_product'
        ]);
        Permission::create([
            'name' => 'Xoá cứng',
            'slug' => 'force_delete_product'
        ]);
        Permission::create([
            'name' => 'Quản lý phân quyền',
            'slug' => 'role_manager'
        ]);

        DB::table('role_permission')->insert([
            ['permission_id' => 1, 'role_id' => 2],
            ['permission_id' => 2, 'role_id' => 2],
            ['permission_id' => 3, 'role_id' => 2],
            ['permission_id' => 4, 'role_id' => 2],
            ['permission_id' => 5, 'role_id' => 2],
            ['permission_id' => 6, 'role_id' => 2],
        ]);
        Schema::enableForeignKeyConstraints();
    }
}

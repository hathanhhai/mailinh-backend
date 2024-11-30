<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('depts')->insert([
            'name' => "Nhân Sự",
            'code' => "hr",
            'note' => "",
            'active' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('depts')->insert([
            'name' => "Kĩ Thuật",
            'code' => "en",
            'note' => "",
            'active' => 1,
            'created_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('dept_roles')->insert([
            'name' => "Tạo User",
            'code' => "DEPARTMENT_READ",
            'api_url' => "department/read",
            'page_url' => "department-read",
            'active' => 1,
            'menu' => 1,
            'menu_parent_id' => null,
            'menu_index' => 1,
            'created_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('dept_roles')->insert([
            'name' => "Sửa User",
            'code' => "USER_READ",
            'api_url' => "users/read",
            'page_url' => "users-update",
            'active' => 1,
            'menu' => 1,
            'menu_parent_id' => null,
            'menu_index' => 1,
            'created_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('dept_roles')->insert([
            'name' => "Tạo User",
            'code' => "USER_CREATE",
            'api_url' => "users/create",
            'page_url' => "users-create",
            'active' => 1,
            'menu' => 1,
            'menu_parent_id' => 2,
            'menu_index' => 1,
            'created_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('dept_roles')->insert([
            'name' => "Báo cáo",
            'code' => "REPORT_READ",
            'api_url' => "report/read",
            'page_url' => "report-read",
            'active' => 1,
            'menu' => 1,
            'menu_parent_id' => null,
            'menu_index' => 1,
            'created_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);



        DB::table('users')->insert([
            'email' => "admin@gmail.com",
            'phone' => "0389938144",
            'first_name' => "admin",
            'last_name' => "tèo",
            'gender' => 1,
            'dept_id' => 1,
            'dept_ids' => "[1]",
            'avatar' => "image.jpg",
            'password'  => Hash::make('123456'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('dept_role_refs')->insert([
            'dept_id' => 1,
            'dept_role_id' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('dept_role_refs')->insert([
            'dept_id' => 1,
            'dept_role_id' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('dept_role_refs')->insert([
            'dept_id' => 1,
            'dept_role_id' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        DB::table('dept_role_refs')->insert([
            'dept_id' => 2,
            'dept_role_id' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}

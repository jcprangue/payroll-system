<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = [
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'first_name' => 'Admin',
            'last_name' => 'Admin',
        ];
        User::insert($admin);
    }
}

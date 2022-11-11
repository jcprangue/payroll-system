<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = [
            [
                'id' => 1,
                'title' => 'Admin'
            ],
            [
                'id' => 2,
                'title' => 'Payroll Officer'
            ]       
        ];
        Role::insert($role);
    }
}

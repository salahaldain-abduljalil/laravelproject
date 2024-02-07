<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class Adminseeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'name'=>'Admin',
            'username'=>'admin',
            'email'=>'salahaldainabduljalil@gmail.com',
            'password'=>Hash::make(123456),

        ]);
    }
}

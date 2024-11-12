<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Arnan',
            'email' => 'arnan@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
            'jabatan' => 'Pegawai',
            'status' => true,
        ]);

        User::create([
            'name' => 'Arya',
            'email' => 'arya@example.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'jabatan' => 'Staf Keuangan',
            'status' => true,
        ]);

        User::create([
            'name' => 'Nanda',
            'email' => 'nanda@example.com',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
            'jabatan' => 'Direktur Utama',
            'status' => true,
        ]);
    }
}
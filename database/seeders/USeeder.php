<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class USeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'login@ledi.app',
            'email_verified_at' => now(),
            'password' => '$2y$10$KtcSx2atgA1YJ8ZUZ7Wo8.n0g57FPFPwELrxaXYsUZltztEO/KtZu',
            'remember_token' => Str::random(10)
        ]);

        User::create([
            'name' => 'superadmin',
            'email' => 'ledi@dev.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$ycP5KufGNysVN7obwT64w.UhGWDM1XBJot8cbkvE7kNDkAkPtoOVi',
            'remember_token' => Str::random(10)
        ]);
    }
}


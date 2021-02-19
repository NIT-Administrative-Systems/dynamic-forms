<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run()
    {
        \App\Models\User::factory(10)->create();
    }
}

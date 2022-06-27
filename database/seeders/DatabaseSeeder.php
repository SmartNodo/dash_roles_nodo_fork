<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(1)->create();
        // \App\Models\State::factory(32)->create();
    }
}

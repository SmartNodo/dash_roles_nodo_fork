<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\ConsultedCredit;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(1)->create();
        // \App\Models\State::factory(32)->create();
        \App\Models\ConsultedCredit::factory(20)->create();

        // $credits = [
        //     '1322026777', 13,
        //     '1922207157', 19,
        //     '0222036775', 2,
        //     '0222048111', 2,
        //     '1422143093', 14,
        //     '2422027618', 24,
        //     '0922067524', 9,
        //     '1422098398', 14,
        //     '1422098730', 14,
        //     '0222041814', 2,
        //     '1422102821', 14,
        //     '2622033740', 26,
        //     '1922200467', 19,
        //     '3022026018', 30,
        //     '0222036802', 2,
        //     '0222038793', 2,
        //     '0222036837', 2,
        //     '0222036799', 2,
        //     '0222036787', 2,
        //     '1522133106', 15
        // ];


        // foreach ($credits as $key => $credit) {
        //     State::create([
        //         'idState_state' => $credit[1],
        //         'credit' => $credit[0],
        //     ]);
        // }


    }
}

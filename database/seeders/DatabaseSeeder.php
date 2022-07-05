<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\State;
use App\Models\AccessKey;
use Illuminate\Support\Facades\Log;

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
        \App\Models\State::factory(32)->create();
        // \App\Models\ConsultedCredit::factory(20)->create();

        $keys = [
            [1, 'IERADM05',	'SMarcos3'],
            [2, 'IERURC06',	'Tecate04'],
            [7, 'IESEEA01',	'Libreta6'],
            [5, 'IEMAPM07',	'Piedras3'],
            [8, 'IEVERL07',	'Meoqui05'],
            [9, 'IEAEFJ05',	'CDMX0004'],
            [10, 'IEVAQC04',	'Palacio4'],
            [11, 'IELOCN04',	'Abasolo4'],
            [12, 'IESEMC02',	'Mexico08'],
            [13, 'IECAJJ01',	'Pachuca4'],
            [14, 'IEEAGR04',	'Acatlan4'],
            [15, 'IEAEFJ05',	'CDMX0004'],
            [16, 'IETORK01',	'Uruapan5'],
            [17, 'IEMESL06',	'Inicio30'],
            [18, 'IECURJ09',	'Saldos09'],
            [19, 'IEPAJJ07',	'Linares4'],
            [21, 'IERACC16',	'Ajalpan4'],
            [22, 'IECUBI01',	'Bernal04'],
            [23, 'IEGUNV01',	'Xcaret05'],
            [24, 'IESEVN01',	'Tamuin04'],
            [25, 'IEVIGA09',	'Mexico42'],
            [26, 'IEMOMV04',	'Nogales4'],
            [28, 'IEGULS04',	'Tampico4'],
            [30, 'IECAGC28',	'Xalapa04'],
            [31, 'IEHECC13',	'Merida05'],
            [32, 'IECURJ09',	'Saldos09'],
            [3, 'IEEELM01',	'Corona51'],
            [6, 'IEGUAG05',	'Tokio226']
        ];


        foreach ($keys as $i => $key) {

            // Log::info('key: ',['0' => $key[0], '1' => $key[1]]);

            AccessKey::create([
                'idState_state' => $key[0],
                'status' => 1,
                'user' => $key[1],
                'pass' => $key[2],
                'error' => ''
            ]);
        }


    }
}

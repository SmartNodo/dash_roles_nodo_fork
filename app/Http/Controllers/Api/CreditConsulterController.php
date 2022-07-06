<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Providers\ScrapeCreditNumber;
use App\Models\ConsultedCredit as Credit;
use App\Models\AccessKey;

class CreditConsulterController extends Controller
{
    public function checkCreditNumber(Request $request)
    {
        // 1.- Obtiene las llaves del estado solicitado:
        $access_key = AccessKey::find($request->idState);

        // Ejecuta el scraper
        $result = event( new ScrapeCreditNumber($request->creditNumber, $access_key->user, $access_key->pass) );

        // dd(isset($result[0]['error']) && $result[0]['error']);

        if(isset($result[0]['error']) && $result[0]['error']) {
            return $result[0];
        }

        // Guarda el crédito 'consultado' con los datos extraídos por el scraper
        Credit::create([
            'idState_state' => $request->idState,
            'user_id' => Auth::user()->id,
            'nss' => $result[0]['nss'],
            'creditNumber' => $request->creditNumber,
            'status' => 'consultado',
            'balance' => $result[0]['costoEcoTec'],
            // 'email' => $result[0][],
            // description => $result[0][],
            // 'isError' => $result[0][],
            'consultedDate' => Carbon::now()
            // 'confirmedDate'
        ]);

        return new JsonResponse([
            'success' => true,
            'status' => 'consultado',
            'result' => $result[0],
            'message' => 'Crédito por consultar guardado correctamente!',
            'creditNumber' => $request->creditNumber
        ], 200);

    }
}

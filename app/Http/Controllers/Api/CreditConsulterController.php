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

    public function listCredits(Request $request) {
        $user_id = auth()->user()->id; //capturamos el ID del usuario

        $credits = Credit::where("user_id", $user_id)
        ->leftJoin('users','users.id','=','user_id')
        ->leftJoin('states','states.idState','=','idState_state')
        ->select('consulted_credits.*','users.name as user','states.name as state')
        ->get();

        return response()->json([
            "status" => 1,
            "msg" => "Credits",
            "data" => $credits
        ]);
    }

}

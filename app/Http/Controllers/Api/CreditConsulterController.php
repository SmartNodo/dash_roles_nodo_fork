<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\TeamUser;
use App\Models\AccessKey;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\ScrapeCreditNumber;
use App\Models\ConsultedCredit as Credit;

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

    public function listCredits(Request $request) {
        $user_id = auth()->user()->id; //capturamos el ID del usuario
        $user_rols = auth()->user()->getRoleNames();

        if($user_rols->contains('Administrador')){
            $credits = Credit::select('consulted_credits.*','users.name as user','states.name as state')
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')                     
            ->get();
        }elseif($user_rols->contains('Manager')){
            $credits = Credit::where("user_id", $user_id)
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')
            ->select('consulted_credits.*','users.name as user','states.name as state')
            ->get();
        }else{
            $credits = Credit::where("user_id", $user_id)
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')
            ->select('consulted_credits.*','users.name as user','states.name as state')
            ->get();
        }

        

        

        return response()->json([
            "status" => 1,
            "msg" => "Credits",
            "data" => $credits
        ]);
    }

}

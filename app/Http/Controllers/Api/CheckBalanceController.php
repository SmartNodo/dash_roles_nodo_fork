<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Providers\ScrapeCreditNumber;
use App\Models\AccessKey;

class CheckBalanceController extends Controller
{
    public function checkBalance(Request $request)
    {
        $creditNumber = $request->creditNumber;

        // 1.- Obtiene las llaves del estado solicitado:
        $access_key = AccessKey::where('idState_state', $request->idState)->first();

        // 2.- Ejecuta el scraper si el acceso es válido.
        $result = event( new ScrapeCreditNumber($request->creditNumber, $access_key->user, $access_key->pass) );

        // 3.- Si la clave tiene status 0 == bloqueada.
        if($access_key->status == 0) {
            $state = $access_key->state->name;
            $result[0]['error'][0] = "Usuario no disponible. <br> Solicita que actualicen el acceso de $state .";
            return $result[0];
        }

        // 4.- Valida si hay algún error
        if(isset($result[0]['error']) && $result[0]['error']) {
            // Valida el error de llave caducada y actualiza el status del acceso si es necesario.
            if(str_contains($result[0]['error'][0], 'Acceso NOK.')) {
                // $this->lockaAccessKey($request->idState, $result[0]['error'][0]);
                Log::debug("status $request->idState de acceso actualizado a bloqueado");
            }

            return $result[0];
        }

        // $result[0]['costoEcoTec'];
        // $result[0]['ahorroEcoSalario'];

        $balance = $result[0]['costoEcoTec'];

        return new JsonResponse([
            'success' => true,
            'result' => $result[0],
            'message' => 'Saldo validado',
            'creditNumber' => $request->creditNumber
        ], 200);
    }
}

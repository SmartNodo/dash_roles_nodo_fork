<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Models\TeamUser;
use App\Models\AccessKey;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\ScrapeCreditNumber;
use App\Models\ConsultedCredit as Credit;

class CreditConsulterController extends Controller
{
    public function checkCreditNumber(Request $request)
    {
        // 1.- Obtiene las llaves del estado solicitado:
        $access_key = AccessKey::where('idState_state', $request->idState)->first();
        // dd($access_key->state);
        // 2.- Si la clave tiene status 0 == bloqueada.
        if($access_key->status == 0) {
            $state = $access_key->state->name;
            $result[0]['error'][0] = "Usuario no disponible. <br> Solicita que actualicen el acceso de $state .";
            return $result[0];
        }

        // 3.- Ejecuta el scraper si el acceso es válido.
        $result = event( new ScrapeCreditNumber($request->creditNumber, $access_key->user, $access_key->pass) );

        // 4.- Valida si hay algún error
        if(isset($result[0]['error']) && $result[0]['error']) {
            // Valida el error de llave caducada y actualiza el status del acceso si es necesario.
            if(str_contains($result[0]['error'][0], 'Acceso NOK.')) {
                $this->lockaAccessKey($request->idState, $result[0]['error'][0]);
                Log::debug("status $request->idState de acceso actualizado a bloqueado");
            }

            return $result[0];
        }

        // 5.- Guarda el crédito 'consultado' con los datos extraídos por el scraper
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

    public function listCredits(Request $request)
    {
        $user_id = auth()->user()->id; //capturamos el ID del usuario
        $user_rols = auth()->user()->getRoleNames();

        // METODO 1
        // $teams = TeamUser::where('user_id',$user_id)->get();
        // $ateams = [];
        // foreach($teams as $k => $v){
        //     array_push($ateams, $v->team_id);
        // }

        // METODO 2
        // Obtenemos los equipos relacionados al usuario logeando
        $idsTeams = TeamUser::where('user_id',$user_id)->selectRaw('group_concat(team_id) as team_ids')->first();
        $idsTeams = explode(",", $idsTeams->team_ids);
        // Obtenemos los usuarios relacionados a los equipos
        $idsUsers = TeamUser::whereIn('team_id',$idsTeams)->selectRaw('group_concat(user_id) as user_ids')->first(); 
        $idsUsers = explode(",", $idsUsers->user_ids);
        // Obtenemos ids de rol sysadmin
        $idsys = User::role('Sysadmin')->selectRaw('group_concat(id) as ids')->first();
        $idsys = explode(",", $idsys->ids);
        // Obtenemos ids de rol Admin
        $idsad = User::role('Administrador')->selectRaw('group_concat(id) as ids')->first();
        $idsad = explode(",", $idsad->ids);


        if($user_rols->contains('Sysadmin')){
            $credits = Credit::select('consulted_credits.*','users.name as user','states.name as state')
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }elseif($user_rols->contains('Administrador')){
            //  Elimina las concidencias en ambos arrays
            $idsUsers = array_diff($idsUsers, $idsys);
            $credits = Credit::whereIn("user_id", $idsUsers)
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')
            ->select('consulted_credits.*','users.name as user','states.name as state')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }elseif($user_rols->contains('Manager')){
            //  Elimina las concidencias en arrays
            $idsUsers = array_diff($idsUsers, $idsys, $idsad);
            $credits = Credit::whereIn("user_id", $idsUsers)
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')
            ->select('consulted_credits.*','users.name as user','states.name as state')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }else{
            $credits = Credit::where("user_id", $user_id)
            ->leftJoin('users','users.id','=','user_id')
            ->leftJoin('states','states.idState','=','idState_state')
            ->select('consulted_credits.*','users.name as user','states.name as state')
            ->orderBy('id', 'DESC')
            ->paginate(10);
        }

        $paginationLinks = (string) $credits->links();

        return response()->json([
            "status" => 1,
            "msg" => "Credits",
            "credits" => $credits,
            "pagination" => $paginationLinks,
        ]);
    }

    public function lockaAccessKey($idState, $error) {
        AccessKey::where('idState_state', $idState)->update([
            'status' => 0,
            'error' => $error
        ]);
        return 0;
    }

}

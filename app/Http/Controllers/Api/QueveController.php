<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Queve;
use Illuminate\Support\Facades\Hash;


class QueveController extends Controller
{
    public function createQueve(Request $request) {        
        //validacion
        $request->validate([
            "nss" => "required",
            "idState" => "required",
        ]);
        // Tenemos que traer el id del usuario logueado
        $user_id = auth()->user()->id;
        $queve = new Queve();    
        $queve->user_id = $user_id; //aqui tenemos el user_id
        $queve->nss = $request->nss;
        $queve->idState = $request->idState;
        $queve->status = 0;
        $queve->balance = 0;
        $queve->email = '';
        $queve->description = '';
        $queve->isError = '';
        

        $queve->save();
        //response
        return response([
            "status" => 1,
            "msg" => "¡Queve creado exitosamente!"
        ]);
    }

    public function listQueve(Request $request) {
        $user_id = auth()->user()->id; //capturamos el ID del usuario

        $queves = Queve::where("user_id", $user_id)->get();

        return response()->json([
            "status" => 1,
            "msg" => "Queves",
            "data" => $queves
        ]);
    }

    public function showQueve($id) {
        $user_id = auth()->user()->id;
        if( Queve::where( ["id" => $id, "user_id" => $user_id ])->exists() ){            
            $info = Queve::where( ["id" => $id, "user_id" => $user_id ])->get();
            return response()->json([
                "status" => 1,
                "msg" => "No se encontró el Queve",
                "msg" => $info,
            ], 404);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Queve"
            ], 404);
        }
    }

    public function updateQueve(Request $request, $id) {
        $user_id = auth()->user()->id;
        if( Queve::where( ["id" => $id, "user_id" => $user_id ])->exists() ){            
            
            $queve = Queve::find($id);    
            $queve->nss = isset($request->nss) ? $request->nss : $queve->nss;
            $queve->idState = isset($request->idState) ? $request->idState : $queve->idState;
            $queve->save();

            return response()->json([
                "status" => 1,
                "msg" => "Queve actualizado",                
            ]);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Queve"
            ], 404);
        }
    }

    public function deleteQueve($id) {
        $user_id = auth()->user()->id;
        if( Queve::where( ["id" => $id, "user_id" => $user_id ])->exists() ){   

            $queve = Queve::where( ["id" => $id, "user_id" => $user_id ])->first();
            $queve->delete();

            return response()->json([
                "status" => 1,
                "msg" => "Queve Eliminado",                
            ]);
        }else{            
            return response()->json([
                "status" => 0,
                "msg" => "No de encontró el Queve"
            ], 404);
        }
    }
    
}

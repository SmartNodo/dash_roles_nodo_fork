<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AccessKey;

class AccessKeyController extends Controller
{
    public function __construct(AccessKey $accessKey)
    {
        $this->accessKey = $accessKey;
    }

    public function index()
    {
        $keys = $this->accessKey->orderBy('idState_state', 'ASC')->with('state')->get();

        return response()->json([
            "status" => 0,
            "msg" => "Llaves de acceso",
            "data" => $keys
        ]);
    }

    public function update(Request $request, $accessKey)
    {
        $key = $this->accessKey->find($accessKey);
        $key->idState_state = $request->idState_state;
        $key->status = 1;
        $key->user = $request->user;
        $key->pass = $request->pass;
        $key->error = '';
        $key->save();

        return response()->json([
            "error" => false,
            "msg" => "Llave de acceso actualizada correctamente",
            "data" => $key
        ]);
    }

    public function disable(Request $request, $accessKey)
    {
        $key = $this->accessKey->find($accessKey);
        $key->status = 2; // disable
        $key->error = 'LLave desactivada';
        $key->save();

        return response()->json([
            "error" => false,
            "msg" => "Llave de acceso desactivada correctamente",
            "data" => $key
        ]);
    }
}

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

        $keys = $this->accessKey->orderBy('status', 'ASC')->with('state')->get();

        return response()->json([
            "status" => 0,
            "msg" => "Llaves de acceso",
            "data" => $keys
        ]);

    }
}

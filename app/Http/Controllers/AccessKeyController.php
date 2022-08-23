<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AccessKey;
use Session;

class AccessKeyController extends Controller
{
 
    public function __construct(AccessKey $accessKey)
    {
        $this->accessKey = $accessKey;
    }

    public function index()
    {
        // $url = "api/list-credits";

        // $_token = Session::get('bearer_token');
        // // dd($_token);

        // $response = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Bearer '.$_token
        // ])->get($url);

        // dd($response);

        // $responseBody = json_decode($response->getBody());

        // // dd($responseBody);

        // return view('access_keys.index', compact('responseBody'));

        return view('access-keys.index');
    }
}

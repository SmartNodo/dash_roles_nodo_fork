<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\ConsultedCredit as Credit;
use App\Models\State;
use App\Providers\ScrapeCreditNumber;
use Illuminate\Support\Facades\DB;

class CreditConsulterController extends Controller
{
    public function index()
    {
        $states = State::all();
        // dd($states);
        return view('credit-consulter.index', ['states' => $states]);
    }

    public function creditos()
    {
        $creditos = Credit::paginate(5);
        return view('creditos.index', compact('creditos'));   
    }

    public function getCreditNumberInfo()
    {
        $client = new Client();
        $url = 'http://proveedoreco.infonavit.org.mx/proveedoresEcoWeb/';

        // Get method: doLogin from homepage form
        $crawler = $client->request('GET', $url);
        $method = $crawler->filter('form input')->attr('value'); // -> doLogin
        $user = 'IERURC06';
        $pass = 'Tecate04';

        $test = $crawler->filter('form')->form([
            'method' => $method,
            'usuario' => $user,
            'password' => $pass,
        ]);
        // dd($test);
        $crawler = $client->submit($test);
        // dd($crawler);

        // Search for errors:
        /*
        error  = response.xpath('//strong[@class="system_title"]/text()').get()
        has_error = response.xpath('//strong[has-class("system_title")]')
        if has_error:
            print(f'++++++++++++++++++++ error BEGIN ++++++++++++++++++++')
            print(error)
            print(has_error)
            save_error(self, error, self.states)
            return False
        */

        $method = $crawler->filter('input[name="method"]')->attr('value');
        $creditNumber = '0222032702';

        $form_credit_number = $crawler->filter('form')->form([
            'method' => $method,
            'numeroCredito' => $creditNumber,
        ]);

        $result = $client->submit($form_credit_number);

        $estado = $result->filter('input[name="estado"]')->attr('value');
        $nombreDH = $result->filter('input[name="nombreDH"]')->attr('value');
        $nss = $result->filter('input[name="nss"]')->attr('value');
        $domicilio = $result->filter('input[name="domicilio"]')->attr('value');
        $codigoPostal = $result->filter('input[name="codigoPostal"]')->attr('value');
        $costoEcoTec = $result->filter('input[name="costoEcoTec"]')->attr('value');
        $ahorroEcoSalario = $result->filter('input[name="ahorroEcoSalario"]')->attr('value');
        $numAvaluo = $result->filter('input[name="numAvaluo"]')->attr('value');
        $usuarioSimulado = $result->filter('input[name="usuarioSimulado"]')->attr('value');
        $estadoMunicipio = $result->filter('input[name="estadoMunicipio"]')->attr('value');
        $marcaVista = $result->filter('input[name="marcaVista"]')->attr('value');
        $isBroxel = $result->filter('input[name="isBroxel"]')->attr('value');

        return view('info',[
            'estado' => $estado,
            'nombreDH' => $nombreDH,
            'nss' => $nss,
            'domicilio' => $domicilio,
            'codigoPostal' => $codigoPostal,
            'costoEcoTec' => $costoEcoTec,
            'ahorroEcoSalario' => $ahorroEcoSalario,
            'numAvaluo' => $numAvaluo,
            'usuarioSimulado' => $usuarioSimulado,
            'estadoMunicipio' => $estadoMunicipio,
            'marcaVista' => $marcaVista,
            'isBroxel' => $isBroxel,
        ]);
    }

    public function saveNewCreditoConsulted(Request $request)
    {

        Credit::create([
            'idState_state' => $request->idState,
            'user_id' => 1,
            'creditNumber' => $request->creditNumber,
            'status' => 'pendiente',
            'consultedDate' => Carbon::now()
        ]);

        $result = event( new ScrapeCreditNumber($request->creditNumber) );


        /* $consulted_credit[0]
            "idCreditType_creditType" => 1
            "createdDate" => "2020-07-22 23:05:25"
            "checkedDate" => "2021-03-28 22:27:47"
            "timesChecked" => 0
            "rfc" => ""
            "applicationStatus" => ""
            "folioNumber" => ""
            "ociCredit" => ""
            "packageNumber" => ""
            "lastUpdateDate" => "0000-00-00"
            "paymentTipe" => ""
            "isOboutToExpire" => 0
            "isInsuredHome" => 0
            "sellersRfc" => ""
            "sellersName" => ""
            "valueNumber" => ""
            "deedNumber" => ""
            "numberOfBeneficiaries" => 0
            "beneficiaryName" => ""
            "beneficiaryKey" => ""
            "address" => ""
            "suburb" => ""
            "zipCode" => ""
            "user" => ""
            "mail" => ""
            "dateOfInsert" => "2021-05-29 21:06:07"
            "dateLastUpdate" => "2021-05-29 21:06:07"
            "curp" => ""
            "statusAux" => 0
        */

        // Guarda la información en la tabla consulted_credits con status = to_consult;
        // Credit::create([
        //     'idState_state' => $consulted_credit->idState_state,
        //     // 'user_id' => Auth::user()->id,
        //     'user_id' => 1,
        //     'creditNumber' => $consulted_credit->creditNumber,
        //     'status' => 'to_consult',
        //     'balance' => $consulted_credit->creditAmount,
        //     'email' => $consulted_credit->mail,
        //     'description' => $consulted_credit->descripcion,
        //     'isError' => 0,
        //     'consultedDate' => Carbon::now(),
        //     // 'confirmedDate' => ,
        // ]);

        return new JsonResponse([
            'success' => true,
            'status' => 'pendiente',
            'result' => $result,
            'message' => 'Crédito por consultar guardado correctamente!',
            'creditNumber' => $request->creditNumber
        ], 200);

    }

}

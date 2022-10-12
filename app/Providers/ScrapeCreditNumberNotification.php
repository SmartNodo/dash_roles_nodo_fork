<?php

namespace App\Providers;

use App\Providers\ScrapeCreditNumber;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
// --
use Illuminate\Http\JsonResponse;
use Goutte\Client;
use Illuminate\Support\Facades\Log;

class ScrapeCreditNumberNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Providers\ScrapeCreditNumber  $event
     * @return void
     */
    public function handle(ScrapeCreditNumber $event)
    {
        // ****** Formulario Login: ******
        $client = new Client();
        $url = 'http://proveedoreco.infonavit.org.mx/proveedoresEcoWeb/';

        // Get method: doLogin from homepage form
        $crawler = $client->request('GET', $url);
        $method = $crawler->filter('form input')->attr('value'); // -> doLogin


        $qry_form = $crawler->filter('form')->form([
            'method' => $method,
            'usuario' => $event->user,
            'password' => $event->pass,
        ]);

        $crawler = $client->submit($qry_form);
        $error = $crawler->filter('.system_title')->each(function ($node) {
            return $node->text();
        });

        if($error) {
            return ['error' => $error];
        }

        /* ------------------------------------------------------ */

        // ****** Formulario de Consulta: ******
        $method = $crawler->filter('input[name="method"]')->attr('value');

        try {
            $form_credit_number = $crawler->filter('form')->form([
                'method' => $method,
                'numeroCredito' => $event->creditNumber,
            ]);

            // Log::info('1.- form_credit_number: ',['form_credit_number' => $form_credit_number]);

        } catch (\Throwable $th) {

            if( $th->getMessage() == 'Unreachable field "numeroCredito".' ) {
                $e = 'Es probable que el número de crédito no corresponda al estado seleccionado';
            } else {
                $e = $th->getMessage();
            }

            return ['error' => $e];
        }

        // Log::info('2.- form_credit_number: ',['form_credit_number' => $form_credit_number]);

        $result = $client->submit($form_credit_number);

        $error = $result->filter('.system_title')->each(function ($node) {
            return $node->text();
        });

        Log::info('estado', ['.' => $result->filter('input[name="estado"]')->attr('value') ]);
        Log::info('nombreDH', ['.' => $result->filter('input[name="nombreDH"]')->attr('value')]);
        Log::info('nss', ['.' => $result->filter('input[name="nss"]')->attr('value')]);
        Log::info('domicilio', ['.' => $result->filter('input[name="domicilio"]')->attr('value')]);
        Log::info('codigoPostal', ['.' => $result->filter('input[name="codigoPostal"]')->attr('value')]);
        Log::info('costoEcoTec', ['.' => $result->filter('input[name="costoEcoTec"]')->attr('value')]);
        // Log::info('ahorroEcoSalario', ['.' => $result->filter('input[name="ahorroEcoSalario"]')->attr('value')]);
        // Log::info('numAvaluo', ['.' => $result->filter('input[name="numAvaluo"]')->attr('value')]);
        // Log::info('usuarioSimulado', ['.' => $result->filter('input[name="usuarioSimulado"]')->attr('value')]);
        // Log::info('estadoMunicipio', ['.' => $result->filter('input[name="estadoMunicipio"]')->attr('value')]);
        // Log::info('marcaVista', ['.' => $result->filter('input[name="marcaVista"]')->attr('value')]);
        Log::info('isBroxel', ['.' => $result->filter('input[name="isBroxel"]')->attr('value')]);
        Log::info('creditNumber', ['.' => $event->creditNumber]);

        if(!$error) {
            return [
                'success' => true,
                'status' => 'consultado',
                'message' => 'Crédito consultado correctamente!',
                'estado' => $result->filter('input[name="estado"]')->attr('value'),
                'nombreDH' => $result->filter('input[name="nombreDH"]')->attr('value'),
                'nss' => $result->filter('input[name="nss"]')->attr('value'),
                'domicilio' => $result->filter('input[name="domicilio"]')->attr('value'),
                'codigoPostal' => $result->filter('input[name="codigoPostal"]')->attr('value'),
                'costoEcoTec' => $result->filter('input[name="costoEcoTec"]')->attr('value'),
                // 'ahorroEcoSalario' => $result->filter('input[name="ahorroEcoSalario"]')->attr('value'),
                // 'numAvaluo' => $result->filter('input[name="numAvaluo"]')->attr('value'),
                // 'usuarioSimulado' => $result->filter('input[name="usuarioSimulado"]')->attr('value'),
                // 'estadoMunicipio' => $result->filter('input[name="estadoMunicipio"]')->attr('value'),
                // 'marcaVista' => $result->filter('input[name="marcaVista"]')->attr('value'),
                'isBroxel' => $result->filter('input[name="isBroxel"]')->attr('value'),
                'creditNumber' => $event->creditNumber
            ];
        }

        return ['error' => $error];

    }
}

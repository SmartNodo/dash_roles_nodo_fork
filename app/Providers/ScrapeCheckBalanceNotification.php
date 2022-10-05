<?php

namespace App\Providers;

use App\Providers\ScrapeCheckBalance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
// --
use Illuminate\Http\JsonResponse;
use Goutte\Client;
use Illuminate\Support\Facades\Log;

class ScrapeCheckBalanceNotification
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
     * @param  \App\Providers\ScrapeCheckBalance  $event
     * @return void
     */
    public function handle(ScrapeCheckBalance $event)
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

        // Log::info('3.- error: ', ['error' => $error]);


        if(!$error) {
            return [
                'success' => true,
                'status' => 'consultado',
                'message' => 'Crédito consultado correctamente!',
                'costoEcoTec' => $result->filter('input[name="costoEcoTec"]')->attr('value'),
            ];
        }

        return ['error' => $error];
    }
}

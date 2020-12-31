<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class RateCityController extends Controller
{
    public function __construct()
    {
    }

    public function listLogisticRateCity()
    {
        $data['title'] = 'Logistic Rate City Management';
        $data['breadcrumbs'] = 'Dashboard / Logistic Rate City Management';

        return view('pages.logistic-rate-city')->with($data);
    }

    public function GetList(Request $request)
    {
        $logistic_id = $request->input('logistic_id');

        if (!$logistic_id) {
            return 'logistic_id is required';
        }
        $rate_id = @$request->input('rate_id');
        $city_id = @$request->input('city_id');
        $data = [
            'logistic_id' => $logistic_id,
            'rate_id' => $rate_id,
            'city_id' => $city_id,
            'page' => ($request->input('start') / $request->input('length')) + 1,
        ];
        $url = http_build_query($data);
        $guzzle = new Client();
        $provincesReq = $guzzle->get(
            env('RATESVC_URL').'/v1/shipment/logistic-rate-city?'.$url,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $provinceRes = json_decode($provincesReq->getBody());
        if ($provincesReq->getStatusCode() == 200) {
            $result = [
                'draw' => $request->input('draw'),
                'recordsFiltered' => $provinceRes->pagination->total_elements,
                'recordsTotal' => $provinceRes->pagination->total_elements,
                'data' => @$provinceRes->data->logistic_rate_city != null ? $provinceRes->data->logistic_rate_city : [],
            ];
        }

        return response()->json($result);
    }

    public function switchLRC(Request $request)
    {
        $data = [
            'data' => [
                'logistic_rate_city' => [
                    'hubless_enabled' => $request->input('prop_hubless') == 'true' ? 1 : 0,
                    'implant_enabled' => $request->input('prop_implant') == 'true' ? 1 : 0,
                    'destination_enabled' => $request->input('prop_destination') == 'true' ? 1 : 0,
                    'local_id' => (int) $request->input('id'),
                    'type' => $request->input('type'),
                ],
            ],
        ];
        $guzzle = new Client();
        try {
            $lrc = $guzzle->put(
                env('RATESVC_URL').'/v1/shipment/logistic-rate-city',
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.OauthClient::getToken(),
                        'x-app-debug' => true,
                    ],
                    'json' => $data,
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['message' => 'something went wrong']);
        }

        $resp = json_decode($lrc->getBody());

        return response()->json($resp);
    }
}

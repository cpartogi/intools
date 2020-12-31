<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class StopRefController extends Controller
{
    public function __construct()
    {
    }

    public function GetList(Request $request)
    {
        $logistic_id = $request->input('logistic_id');

        if (!$logistic_id) {
            return 'logistic_id is required';
        }
        $rate_id = @$request->input('rate_id');
        $suburb_id = @$request->input('suburb_id');
        $area_id = @$request->input('area_id');
        $data = [
            'logistic_id' => $logistic_id,
            'rate_id' => $rate_id,
            'suburb_id' => $suburb_id,
            'area_id' => $area_id,
            'page' => ($request->input('start') / $request->input('length')) + 1,
        ];
        $url = http_build_query($data);
        $guzzle = new Client();
        $stoprefReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/stop_link?'.$url,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                    'Cache-Control' => 'must-revalidate',
                ],
            ]
        );

        $stoprefRes = json_decode($stoprefReq->getBody());
        if ($stoprefReq->getStatusCode() == 200) {
            $result = [
                'draw' => $request->input('draw'),
                'recordsFiltered' => $stoprefRes->pagination->total_elements,
                'recordsTotal' => $stoprefRes->pagination->total_elements,
                'data' => @$stoprefRes->data->stop_link != null ? $stoprefRes->data->stop_link : [],
            ];
        }

        return response()->json($result);
    }

    public function updateStopRef(Request $request)
    {
        $data = [
            'data' => [
                'stop_ref' => [
                    'logistic_id' => (int) $request->input('logistic_id'),
                    'new_ref' => $request->input('new_stopref'),
                    'old_ref' => $request->input('old_stopref'),
                    'stop_id' => (int) $request->input('stop_id'),
                ],
            ],
        ];

        $guzzle = new Client();
        $stoprefReq = $guzzle->put(
            env('RATESVC_URL').'/v1/location/stop_link',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
                'json' => $data,
            ]
        );

        $stoprefRes = json_decode($stoprefReq->getBody());

        return response()->json($stoprefRes);
    }
}

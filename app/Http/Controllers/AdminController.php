<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function healthz()
    {
        $guzzle = new Client();

        $healthAccount = $guzzle->get(
            env('ACCOUNTSVC_URL').'/healthz'
        );
        $healthRate = $guzzle->get(
            env('RATESVC_URL').'/healthz'
        );
        // TODO
        // check ping to mysql

        if ($healthAccount->getStatusCode() == 200 && $healthRate->getStatusCode() == 200) {
            return response('', 200);
        }

        return response('', 200);
    }

    public function dashboard()
    {
        $data['title'] = 'Dashboard';
        $data['breadcrumbs'] = 'Dashboard';

        return view('pages.dashboard')->with($data);
    }

    public function getCountry(Request $request)
    {
        $query = $request->input('term');
        $guzzle = new \GuzzleHttp\Client();
        $token = OauthClient::getToken();
        $countryReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/country?name='.$query,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ],
            ]
        );
        if ($countryReq->getStatusCode() == 200) {
            $countryRes = json_decode($countryReq->getBody());
            $countries = $countryRes->data->countries;
            $json = [];
            foreach ($countries as $key) {
                $json[] = ['id' => $key->id, 'text' => $key->name];
            }
            $data = [
                'total_count' => count($countries),
                'complete' => false,
                'items' => $json,
            ];

            return response()->json($json);
        }

        return response()->status(400);
    }

    public function liststopref()
    {
        $guzzle = new \GuzzleHttp\Client();
        $token = OauthClient::getToken();

        $countryReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/country?sort_by=country_id,-country_name&limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                ],
            ]
        );

        $countryRes = json_decode($countryReq->getBody());
        $countries = $countryRes->data->countries;
        $data['title'] = 'Stop Ref Update';
        $data['breadcrumbs'] = 'Stopref';

        return view('layouts.pages.stopref', ['countries' => $countries])->with($data);
    }

    public function listProvinces(Request $request, $countryID)
    {
        $guzzle = new Client();

        $provincesReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/province?country_id='.$countryID.'&sort_by=province_name,-province_status&limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $provinceRes = json_decode($provincesReq->getBody());
        $provinces = $provinceRes->data->provinces;

        return $provinces;
    }

    public function listCities(Request $request, $provinceID)
    {
        $guzzle = new Client();

        $cityReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/city?province_id='.$provinceID.'&sort_by=city_name,-province_name&limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $cityRes = json_decode($cityReq->getBody());
        $cities = $cityRes->data->cities;

        return $cities;
    }

    public function listSuburbs(Request $request, $cityID)
    {
        $guzzle = new Client();

        $suburbReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/suburb?city_id='.$cityID.'&sort_by=suburb_name,-city_name&limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $suburbRes = json_decode($suburbReq->getBody());
        $suburbs = $suburbRes->data->suburbs;

        return $suburbs;
    }

    public function listAreas(Request $request, $suburbID)
    {
        $guzzle = new Client();

        $AreaReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/area?suburb_id='.$suburbID.'&sort_by=area_name,-suburb_name&limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $areaRes = json_decode($AreaReq->getBody());
        $areas = $areaRes->data->areas;

        return $areas;
    }

    public function getLogistics()
    {
        $guzzle = new Client();

        $logisticReq = $guzzle->get(
            env('RATESVC_URL').'/v1/shipment/logistic?limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $logisticRes = json_decode($logisticReq->getBody());
        $logistics = $logisticRes->data->logistics;

        return $logistics;
    }

    public function getRates($logisticID)
    {
        $guzzle = new Client();
        $rateReq = $guzzle->get(
            env('RATESVC_URL').'/v1/shipment/rate?logistic_id='.$logisticID.'&sort_by=logistic_name&limit=500',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $rateRes = json_decode($rateReq->getBody());
        $rates = $rateRes->data->rates;

        return $rates;
    }

    public function getAreas(Request $request)
    {
        $guzzle = new Client();
        $name = $request->input('q');
        $AreaReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/area?area_name='.$name.'%&limit=10',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $areaRes = json_decode($AreaReq->getBody());
        $areas = $areaRes->data->areas;
        $areas = array_map(function ($item) {
            return [
                 'id' => $item->id,
                 'text' => $item->name.', '.$item->suburb->name.', '.$item->city->name.', '.$item->province->name.', '.$item->country->name,
             ];
        }, $areas);

        return [
            'results' => $areas,
        ];
    }

    public function GetLogisticStopRef(Request $request)
    {
        $guzzle = new Client();
        $guzzle = new Client();
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
            'limit' => 200,
        ];
        $url = http_build_query($data);
        $cityReq = $guzzle->get(
            env('RATESVC_URL').'/v1/shipment/logistic?'.$url,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $cityReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/stop_link?limit=200',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $cityRes = json_decode($cityReq->getBody());
        $cities = $cityRes->data->logistics;

        return $cities;
    }
}

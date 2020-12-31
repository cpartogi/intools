<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use App\Rules\NotOnlySpace;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class RateServiceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'logistic_id' => 'required',
            'rate_name' => ['required', 'min:3', new NotOnlySpace()],
            'rate_desc' => 'required',
            'rate_full' => 'required',
            'rate_type' => 'required',
            'show_id' => 'required',
        ]);

        $data = [
        'data' => [
          'rate' => [
            'fee_for_hub' => (int) $request->get('fee_for_hub'),
            'fee_for_ma' => (int) $request->get('fee_for_ma'),
            'fee_logistic' => (int) $request->get('fee_logistic'),
            'fee_vendor' => (int) $request->get('fee_vendor'),
            'is_inclusive' => $request->input('is_inclusive') == 'true' ? true : false,
            'is_pickup_by_agent' => $request->input('is_pickup_by_agent') == 'true' ? true : false,
            'is_using_latlong' => $request->input('is_using_latlong') == 'true' ? true : false,
            'logistic_id' => (int) $request->get('logistic_id'),
            'max_kg' => (int) $request->get('max_kg'),
            'min_kg' => (int) $request->get('min_kg'),
            'ppn' => (int) $request->get('ppn'),
            'rate_desc' => $request->get('rate_desc'),
            'rate_full' => $request->get('rate_full'),
            'rate_name' => $request->get('rate_name'),
            'rate_ref' => $request->get('rate_ref'),
            'rate_type' => (int) $request->get('rate_type'),
            'show_id' => (int) $request->get('show_id'),
            'volumetric' => (int) $request->get('volumetric'),
          ],
        ],
      ];
        $guzzle = new Client();

        try {
            $rateReq = $guzzle->request(
              'POST',
              env('RATESVC_URL').'/v1/shipment/rate',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $rateRes = json_decode($rateReq->getBody());
            if ($rateReq->getStatusCode() == 200) {
                $result = $rateRes->data->rate;
            }

            return redirect()->route('rateService')->with('success', 'Rate Berhasil Dibuat');
        } catch (\Exception $ex) {
            return redirect()->route('rateService')->with('alert', $ex->getMessage());
        }
    }

    public function create()
    {
        $data['title'] = 'Create Rate Service';
        $data['breadcrumbs'] = 'Create Rate Service';

        return view('pages.rate-service-create')->with($data);
    }

    public function list()
    {
        $data['title'] = 'Rate Service Management';
        $data['breadcrumbs'] = 'Rate Service Management';

        return view('pages.rate-service')->with($data);
    }

    public function GetList(Request $request)
    {
        $search = $request->input('search')['value'];

        $data = [
            'page' => ($request->input('start') / $request->input('length')) + 1,
            'limit' => $request->input('length'),
            'rate_name' => '%'.$search.'%',
        ];
        $url = http_build_query($data);
        $guzzle = new Client();
        $rateReq = $guzzle->get(
            env('RATESVC_URL').'/v1/shipment/rate?'.$url,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                ],
            ]
        );

        $rateRes = json_decode($rateReq->getBody());
        if ($rateReq->getStatusCode() == 200) {
            $result = [
                'draw' => $request->input('draw'),
                'recordsFiltered' => $rateRes->pagination->total_elements,
                'recordsTotal' => $rateRes->pagination->total_elements,
                'data' => @$rateRes->data->rates != null ? $rateRes->data->rates : [],
            ];
        }

        return response()->json($result);
    }
}

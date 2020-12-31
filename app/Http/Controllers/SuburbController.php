<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use App\Rules\NotOnlySpace;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SuburbController extends Controller
{
    public function getList(Request $request)
    {
        $request->validate([
            'cityID' => 'required',
            'provinceID' => 'required',
        ]);

        $cityID = @$request->input('cityID');
        $provinceID = @$request->input('provinceID');
        $countryID = @$request->input('countryID');
        $data = [
          'city_id' => $cityID,
          'province_id' => $provinceID,
          'country_id' => $countryID,
          'page' => ($request->input('start') / $request->input('length')) + 1,
        ];

        $url = http_build_query($data);
        $guzzle = new Client();
        $suburbReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/suburb?'.$url,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                    'Cache-Control' => 'must-revalidate',
                ],
            ]
        );

        $suburbRes = json_decode($suburbReq->getBody());
        if ($suburbReq->getStatusCode() == 200) {
            $result = [
              'draw' => $request->input('draw'),
              'recordsFiltered' => $suburbRes->pagination->total_elements,
              'recordsTotal' => $suburbRes->pagination->total_elements,
              'data' => @$suburbRes->data->suburbs != null ? $suburbRes->data->suburbs : [],
            ];
        }

        return response()->json($result);
    }

    public function list()
    {
        $data['title'] = 'Suburb Management';
        $data['breadcrumbs'] = 'Suburb Management';

        return view('pages.suburb')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Suburb';
        $data['breadcrumbs'] = 'Create Suburb';

        return view('pages.suburb-create')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'city_id' => 'required',
            'name' => ['required', 'min:3', new NotOnlySpace()],
        ]);

        $data = [
        'data' => [
          'suburb' => [
            'city_id' => (int) $request->get('city_id'),
            'lat' => (float) $request->get('lat'),
            'lng' => (float) $request->get('lng'),
            'name' => $request->get('name'),
            'status' => $request->input('status') == 'true' ? true : false,
          ],
        ],
      ];
        $guzzle = new Client();

        try {
            $suburbReq = $guzzle->request(
              'POST',
              env('RATESVC_URL').'/v1/location/suburb',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $suburbRes = json_decode($suburbReq->getBody());
            if ($suburbReq->getStatusCode() == 200) {
                $result = $suburbRes->data->suburb;
            }

            return redirect()->route('suburb')->with('success', 'Suburb Berhasil Dibuat');
        } catch (\Exception $ex) {
            return redirect()->route('suburb')->with('alert', $ex->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit Suburb';
        $data['breadcrumbs'] = 'Edit Suburb';

        $guzzle = new Client();
        $suburbReq = $guzzle->get(
            env('RATESVC_URL').'/v1/location/suburb/'.$id,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                    'Cache-Control' => 'must-revalidate',
                ],
            ]
        );

        $suburbRes = json_decode($suburbReq->getBody());
        if ($suburbReq->getStatusCode() == 200) {
            $result = $suburbRes->data->suburb;
            $data['result'] = $result;
        }

        return view('pages.suburb-edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'city_id' => 'required',
            'name' => ['required', 'min:3', new NotOnlySpace()],
        ]);

        $data = [
        'data' => [
          'suburb' => [
            'city_id' => (int) $request->get('city_id'),
            'lat' => (float) $request->get('lat'),
            'lng' => (float) $request->get('lng'),
            'name' => $request->get('name'),
            'status' => $request->input('status') == 'true' ? true : false,
          ],
        ],
      ];
        $guzzle = new Client();

        try {
            $suburbReq = $guzzle->request(
              'PUT',
              env('RATESVC_URL').'/v1/location/suburb/'.$id,
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $suburbRes = json_decode($suburbReq->getBody());
            if ($suburbReq->getStatusCode() == 200) {
                $result = $suburbRes->data->suburb;
            }

            return redirect()->route('suburb')->with('success', 'Suburb Berhasil Diupdate');
        } catch (\Exception $ex) {
            return redirect()->route('suburb')->with('alert', $ex->getMessage());
        }
    }
}

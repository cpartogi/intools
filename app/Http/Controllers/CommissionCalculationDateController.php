<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class CommissionCalculationDateController extends Controller
{
    public function getList(Request $request)
    {
        $order_id = $request->input('order_id');
        if (!$order_id) {
            return 'order_id is required';
        }

        $guzzle = new Client();
        $req = $guzzle->get(
            env('BILLINGSVC_URL').'/v1/billing/commission/order/'.$order_id,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                    'Cache-Control' => 'must-revalidate',
                ],
            ]
        );

        $resp = json_decode($req->getBody());
        if ($req->getStatusCode() == 200) {
            $result = [
                'data' => [$resp->data->commission],
            ];
        }

        return response()->json($result);
    }

    public function list()
    {
        $data['title'] = 'Commission Calculation Schedule';
        $data['breadcrumbs'] = 'Commission Calculation Schedule';

        return view('pages.commission-calculation-schedule')->with($data);
    }

    public function create()
    {
        $data['title'] = 'Create Commission Calculation Schedule';
        $data['breadcrumbs'] = 'Create Commission Calculation Schedule';

        return view('pages.commission-calculation-schedule-create')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'calculation_date' => 'required|date|date_format:Y-m-d|after_or_equal:today',
        ]);

        try {
            $userEmail = auth()->user()->email;
        }catch(\Exception $ex) {
            $userEmail = "";
        }

        $data = [
            'data' => [
                'commission' => [
                    'order_id' => (string) $request->get('order_id'),
                    'calculation_date' => (string) $request->get('calculation_date'),
                    'action_by' => $userEmail,
                ],
            ],
        ];

        $guzzle = new Client();
        try {
            $req = $guzzle->request(
              'PATCH',
              env('BILLINGSVC_URL').'/v1/billing/commission/agent/calculation',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $res = json_decode($req->getBody());
            if ($req->getStatusCode() == 200) {
                $result = $res->data->commission;
            }

            return response('Commission calculation date berhasil dibuat', 200);
        } catch (\Exception $ex) {
            $message = "Terjadi kesalahan";
            if ($ex->hasResponse()) {
                try {
                    $response= $ex->getResponse()->getBody()->getContents();
                    $responseJSON = json_decode($response,true);
                    $message = $responseJSON['metadata']['error']['message'];
                } catch (\Throwable $th) {}
            }
            return response(['msg' =>$message], 400);
        }
    }

    public function edit(Request $request, $id)
    {
        $data['title'] = 'Edit Commission Calculation Schedule';
        $data['breadcrumbs'] = 'Edit Commission Calculation Schedule';

        $guzzle = new Client();
        $req = $guzzle->get(
            env('BILLINGSVC_URL').'/v1/billing/commission/order/'.$id,
            [
                'headers' => [
                    'Authorization' => 'Bearer '.OauthClient::getToken(),
                    'Cache-Control' => 'must-revalidate',
                ],
            ]
        );

        $res = json_decode($req->getBody());
        if ($req->getStatusCode() == 200) {
            $result = $res->data->commission;
            $data['result'] = $result;
        }

        return view('pages.commission-calculation-schedule-edit')->with($data);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'is_processed' => 'required',
            'calculation_date' => 'required|date',
        ]);

        try {
            $userEmail = auth()->user()->email;
        }catch(\Exception $ex) {
            $userEmail = "";
        }

        $data = [
            'data' => [
                'commission' => [
                    'order_id' => $id,
                    'is_processed' => (string) $request->get('is_processed'),
                    'calculation_date' => (string) $request->get('calculation_date'),
                    'action_by' => $userEmail,
                ],
            ],
        ];

        $guzzle = new Client();
        try {
            $req = $guzzle->request(
              'PATCH',
              env('BILLINGSVC_URL').'/v1/billing/commission/agent/calculation',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $res = json_decode($req->getBody());
            if ($req->getStatusCode() == 200) {
                $result = $res->data->commission;
            }

            return response('Commission calculation date berhasil diupdate', 200);
        } catch (\Exception $ex) {
            $message = "Terjadi kesalahan";
            if ($ex->hasResponse()) {
                try {
                    $response= $ex->getResponse()->getBody()->getContents();
                    $responseJSON = json_decode($response,true);
                    $message = $responseJSON['metadata']['error']['message'];
                } catch (\Throwable $th) {}
            }
            return response(['msg' =>$message], 400);
        }
    }

}

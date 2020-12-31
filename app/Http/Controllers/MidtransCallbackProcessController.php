<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class MidtransCallbackProcessController extends Controller
{
    public function list()
    {
        $data['title'] = 'Midtrans Callback Process';
        $data['breadcrumbs'] = 'Midtrans Callback Process';

        return view('pages.midtrans-callback-process')->with($data);
    }

    public function hitApi(Request $request)
    {
        $request->validate([
            'request_payload' => 'required'
        ]);

        $request_payload = $request->get('request_payload');
        try{
            $data = json_decode($request_payload);
            if($data->is_old != 1) {
            return response(['msg' => 'Nilai pada field is_old harus 1'], 400);
            }
            else {
                if($data->log_id == 0 || $data->log_id == "") {
                return response(['msg' => 'Nilai pada field log_id tidak boleh 0 atau kosong'], 400);
            }
            }
        }
        catch(\Exception $ex){
            return response(['msg' => 'Periksa kembali input'], 400);
        }

        $guzzle = new Client();
        try {
            $dataReq = $guzzle->request(
              'POST',
              env('DEPOSITSVC_URL').'/v1/deposit/agent/midtrans/callback',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $dataRes = json_decode($dataReq->getBody());
            if ($dataReq->getStatusCode() == 200) {
                $result = $dataRes;
            }

            return response('Midtrans Callback Process Sukses', 200);
        }
        catch (\Exception $ex) {
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

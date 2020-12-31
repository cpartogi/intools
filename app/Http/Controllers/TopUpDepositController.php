<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use DB;

class TopUpDepositController extends Controller
{

    public function create()
    {
        $data['title'] = 'Top Up Deposit Manual';
        $data['breadcrumbs'] = 'Top Up Deposit Manual';

        return view('pages.topup-deposit-create')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required',
            'amount' => 'required|gt:0',
            'log_description' => 'required',
        ]);

        try {
            $userEmail = auth()->user()->email;
        }catch(\Exception $ex) {
            $userEmail = "";
        }

        // Check user whether logged in user able to manipulate deposit
        $user = DB::table('users')
            ->select('id','name','email')
            ->where('email', '=', $userEmail)
            ->first();

        if($user == null) {
            return response(['msg' => 'User tidak diperbolehkan untuk melakukan koreksi deposit'], 400);
        }

        // default value
        $is_old = 0;
        $log_type = 1;
        $uom = 'IDR';
        $order_id = "";
        $version = 1;

        $data = [
            'data' => [
                'deposit' => [
                    'agent_id' => (int) $request->get('agent_id'),
                    'amount' => (int) $request->get('amount'),
                    'created_by' => $user->id,
                    'is_old' => $is_old,
                    'log_description' => (string) $request->get('log_description'),
                    'log_type' => $log_type,
                    'order_id' => $order_id,
                    'uom' => $uom,
                    'version' => $version,
                ],
            ],
        ];

        $guzzle = new Client();
        try {
            $req = $guzzle->request(
              'POST',
              env('DEPOSITSVC_URL').'/v1/deposit/agent/transaction',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $res = json_decode($req->getBody());
            if ($req->getStatusCode() == 200) {
                $result = $res->data->deposit;
            }

            return response("Top up berhasil", 200);
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

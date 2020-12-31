<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use DB;

class DepositManipulationController extends Controller
{

    public function create()
    {
        $data['title'] = 'Deposit Manipulation';
        $data['breadcrumbs'] = 'Deposit Manipulation';

        return view('pages.deposit-manipulation-create')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'agent_id' => 'required',
            'amount' => 'required',
            'description' => 'required',
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
        $is_old = 0; // since is_old = 0, log_id is not required
        $log_type = 16;
        $uom = 'IDR';
        $version = 0;

        $data = [
            'data' => [
                'deposit' => [
                    'agent_id' => (int) $request->get('agent_id'),
                    'amount' => (int) $request->get('amount'),
                    'created_by' => $user->id,
                    'is_old' => $is_old,
                    'log_description' => (string) $request->get('description'),
                    'log_type' => $log_type,
                    'order_id' => (string) $request->get('order_id'),
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

            $message = "Koreksi deposit untuk order id ".$request->get('order_id')." berhasil";
            return response($message, 200);
        } catch (RequestException $ex) {
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

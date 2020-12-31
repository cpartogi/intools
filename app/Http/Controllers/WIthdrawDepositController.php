<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class WithdrawDepositController extends Controller
{

    public function create()
    {
        $data['title'] = 'Withdraw Deposit';
        $data['breadcrumbs'] = 'Withdraw Deposit';

        return view('pages.withdraw-deposit-create')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required',
            'agent_id' => 'required|integer',
            'account_name' => 'required',
            'account_no' => 'required',
            'bank_id' => 'required|integer',
            'branch_name' => 'required',
            'request_date' => 'required|date|after_or_equal:today',
            'amount' => 'required|integer|lt:0',
            'log_description' => 'required',
            'notes' => 'required',
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

        $data = [
            'data' => [
                'withdraw' => [
                    'account_name'=> (string) $request->get('account_name'),
                    'account_no'=> (string) $request->get('account_no'),
                    'agent_id'=> (int) $request->get('agent_id'),
                    'amount'=> (int) $request->get('amount'),
                    'bank_id'=> (int) $request->get('bank_id'),
                    'branch_name'=> (string) $request->get('branch_name'),
                    'created_by'=> $user->id,
                    'is_old'=> 0,
                    'log_description'=> (string) $request->get('log_description'),
                    'notes'=> (string) $request->get('notes'),
                    'request_date'=> $request->get('request_date'),
                    'status'=> 1,
                    'transaction_id'=> (string) $request->get('transaction_id'),
                    'uom'=> 'IDR',
                ],
            ],
        ];

        $guzzle = new Client();
        try {
            $req = $guzzle->request(
              'POST',
              env('DEPOSITSVC_URL').'/v1/deposit/agent/transaction/withdraw',
              [
                  'headers' => [
                      'Authorization' => 'Bearer '.OauthClient::getToken(),
                  ],
                  'json' => $data,
              ]
            );

            $res = json_decode($req->getBody());
            if ($req->getStatusCode() == 200) {
                $result = $res->data->withdraw;
            }

            return response('Withdraw deposit sukses', 200);
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

    public function getBanks(Request $request)
    {
        $guzzle = new Client;
        $AreaReq = $guzzle->get(
            env("DEPOSITSVC_URL") . '/v1/deposit/payment?type=1',
            [
                'headers' => [
                    "Authorization" => "Bearer " . OauthClient::getToken(),
                ],
            ]
        );

        $res = json_decode($AreaReq->getBody());
        $banks = $res->data->payment;
        $banks = array_map(function($item) {
             return [
                 "id"=> $item->id,
                 "name"=>$item->name,
             ];
        }, $banks);

        return [
            "results"=> $banks
        ];
    }

}

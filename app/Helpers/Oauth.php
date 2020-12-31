<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class OauthClient
{
    public static function getToken()
    {
        $token = Cache::get("service_token");

        if ($token == "") {
            $guzzle = new \GuzzleHttp\Client;

            $request = $guzzle->post(
                env('ACCOUNTSVC_URL').'/v1/oauth2/token',
                [
              'header' => [
                "Content-Type"=> "application/x-www-form-urlencoded",
                "x-app-debug"=>true
              ],
              'auth'=>[
                env("AUTH_CLIENT_ID"),
                env("AUTH_CLIENT_SECRET"),
              ],
              'form_params'=>[
                'grant_type' => 'client_credentials',
                'scope'=>'rate.shp'
              ]
            ]
            );
            if ($request->getStatusCode() == 200) {
                $response = json_decode($request->getBody());
                $token = $response->access_token;
                Cache::put("service_token", $token, $response->expires_in);
            }
            return false;
        }

        return $token;
    }
}

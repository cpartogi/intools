<?php

namespace App\Http\Controllers;

use App\Helpers\OauthClient;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class ShipmentPricingController extends Controller
{
    public function __construct()
    {
    }

    public function search(Request $request)
    {

        $origin_area_id = $request->input('origin_area_id');
        if (!$origin_area_id) {
            return 'origin_area_id is required';
        }

        $destination_area_id = $request->input('destination_area_id');
        if (!$destination_area_id) {
            return 'destination_area_id is required';
        }

        $item_value = $request->input('item_value');
        if (!$item_value) {
            return 'item_value is required';
        }

        $item_length = $request->input('item_length');
        if (!$item_length) {
            return 'item_length is required';
        }

        $item_width = $request->input('item_width');
        if (!$item_width) {
            return 'item_width is required';
        }

        $item_height = $request->input('item_height');
        if (!$item_height) {
            return 'item_height is required';
        }

        $item_weight = $request->input('item_weight');
        if (!$item_weight) {
            return 'item_weight is required';
        }

        $data = [
            'origin_area_id' => $origin_area_id,
            'destination_area_id' => $destination_area_id,
            'item_value' => $item_value,
            'length' => $item_length,
            'width' => $item_width,
            'height' => $item_height,
            'weight' => $item_weight,
            'valid_to_order' => false,
            'client_id' => 1,
            'page' => ($request->input('start') / $request->input('length')) + 1,
        ];

        $url = http_build_query($data);
        try {
            $guzzle = new Client();
            $pricingReq = $guzzle->get(
                env('RATESVC_URL').'/v1/shipment/pricing/domestic?'.$url,
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.OauthClient::getToken(),
                        'Cache-Control' => 'must-revalidate',
                    ],
                ]
            );
            $pricingRes = json_decode($pricingReq->getBody());
            if ($pricingReq->getStatusCode() == 200) {
                $result = [
                    'draw' => $request->input('draw'),
                    'recordsFiltered' => $pricingRes->pagination->total_elements,
                    'recordsTotal' => $pricingRes->pagination->total_elements,
                    'data' => @$pricingRes->data->pricings != null ? $pricingRes->data->pricings : [],
                ];
            }
        } catch (\Exception $e) {
            $result = [
                'draw' => $request->input('draw'),
                'recordsFiltered' => 0,
                'recordsTotal' => 0,
                'data' =>  [],
            ];
        }



        return response()->json($result);
    }

    public function list()
    {
        $data['title'] = 'Shipment Pricing';
        $data['breadcrumbs'] = 'Shipment Pricing';
        return view('layouts.pages.shipment-pricing')->with($data);
    }
}

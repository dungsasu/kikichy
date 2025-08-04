<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\admin\Config\Config;

class EcountService
{
    protected $comCode;
    protected $zone;
    protected $userId;
    protected $apiCertKey;
    protected $baseUrl;
    protected $header; 

    public function __construct()
    {
        $this->comCode = config('services.ecount.com_code');
        $this->zone = config('services.ecount.zone');
        $this->userId = config('services.ecount.user_id');
        $this->apiCertKey = config('services.ecount.api_cert_key');
        $this->baseUrl = config('services.ecount.base_url');
        $this->header = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        $this->baseUrl = str_replace('{ZONE}', $this->zone, $this->baseUrl); 
    }

    public function getZone()
    {
        $url = str_replace('{ZONE}', '', $this->baseUrl);
        $data = [
            'COM_CODE' => $this->comCode,
        ];

        $respone = Http::withHeaders($this->header)
            ->post(
                $url . '/Zone',
                $data
            );

        return $respone->json();
    }

    public function login()
    {
        $ecountSession = Config::where('alias', 'ecount_session_id')->first();

        $updatedAt = Carbon::parse($ecountSession->updated_at);
        $currentTime = Carbon::now();

        if (!$ecountSession->value || $currentTime->diffInHours($updatedAt) >= 1) { 
            $data = [
                'COM_CODE' => $this->comCode,
                'USER_ID' => $this->userId,
                'API_CERT_KEY' => $this->apiCertKey,
                'LAN_TYPE' => 'vi-VN',
                'ZONE' => $this->zone,
            ];
            
            $respone = Http::withHeaders($this->header)
                ->post(
                    $this->baseUrl . '/OAPILogin',
                    $data
                );
            $result = $respone->json();
            
            if (@$result['Errors'] != null && $result['Error'] != null) {
                throw new \Exception($result['Error']['Message']);
            }

            $ecountSession->update([
                'value' => $result['Data']['Datas']['SESSION_ID'],
            ]);

            return $result['Data']['Datas']['SESSION_ID'];
        } else {
            return $ecountSession->value;
        }
    }

    public function getQuantityProducts()
    {
        $sessionId = $this->login();
        $data = [
            'SESSION_ID' => $sessionId,
            'BASE_DATE' => Carbon::now()->format('Ymd'),
        ];

        $respone = Http::withHeaders($this->header)
            ->post(
                $this->baseUrl . "/InventoryBalance/GetListInventoryBalanceStatus?SESSION_ID=$sessionId",
                $data
            );
        $result = $respone->json();

        if (@$result['Error'] != null && @$result['Errors'] != null) {
            throw new \Exception($result['Errors'][0]['Message']);
        }

        return $result['Data']['Result'];
    }

    public function getQuantityProduct($productCode)
    {
        $sessionId = $this->login();
         
        $data = [
            'SESSION_ID' => $sessionId,
            'BASE_DATE' => Carbon::now()->format('Ymd'),
            'PROD_CD' => $productCode,
        ];

        $respone = Http::withHeaders($this->header)
            ->post(
                $this->baseUrl . "/InventoryBalance/ViewInventoryBalanceStatus?SESSION_ID=$sessionId",
                $data
            );
        $result = $respone->json(); 

        if (@$result['Error'] != null && @$result['Errors'] != null) {
            throw new \Exception($result['Errors'][0]['Message']);
        }

        return $result['Data']['Result'];
    }
}

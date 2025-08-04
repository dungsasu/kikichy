<?php

namespace App\Services\Sms;

use App\Models\admin\SmsHistory\SmsHistory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $apiUrl;
    protected $apiKey;
    protected $username;
    protected $password;

    public function __construct()
    {
        // Cấu hình API SMS - bạn có thể thêm vào file .env
        $this->apiUrl = config('sms.api_url', 'https://api.sms-provider.com/v1/send');
        $this->apiKey = config('sms.api_key', '');
        $this->username = config('sms.username', '');
        $this->password = config('sms.password', '');
    }

    /**
     * Gửi SMS
     */
    public function sendSms($phone, $message, $type = 1, $sender = 'DMCFASHION')
    {
        try {
            $requestId = 'REQ_' . time() . '_' . rand(1000, 9999);
            
            $smsData = [
                'phone' => $phone,
                'telco' => $this->detectTelco($phone),
                'type' => $type,
                'sender' => $sender,
                'message' => $message,
                'request_id' => $requestId,
                'use_unicode' => $this->hasUnicodeChars($message) ? 1 : 0,
                'msg_length' => strlen($message),
                'mt_count' => ceil(strlen($message) / ($this->hasUnicodeChars($message) ? 70 : 160)),
                'account' => 'DMCFASHION',
                'referent_id' => null,
            ];

            // Gọi API gửi SMS thực tế
            $response = $this->callSmsApi($smsData);

            // Lưu log vào database
            $smsData['status'] = $response['success'] ? 'success' : 'failed';
            $smsData['error_code'] = $response['error_code'] ?? null;
            $smsData['error_message'] = $response['error_message'] ?? null;
            $smsData['response_data'] = json_encode($response);

            $smsLog = SmsHistory::create($smsData);

            return [
                'success' => $response['success'],
                'message' => $response['message'] ?? 'SMS sent',
                'sms_id' => $smsLog->id,
                'request_id' => $requestId
            ];

        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            
            // Lưu log lỗi
            SmsHistory::create([
                'phone' => $phone,
                'telco' => $this->detectTelco($phone),
                'type' => $options['type'] ?? 1,
                'sender' => $options['sender'] ?? 'DMCFASHION',
                'message' => $message,
                'request_id' => 'REQ_' . time() . '_' . rand(1000, 9999),
                'use_unicode' => $options['use_unicode'] ?? 0,
                'msg_length' => strlen($message),
                'mt_count' => ceil(strlen($message) / ($options['use_unicode'] ? 70 : 160)),
                'account' => $options['account'] ?? 'DMCFASHION',
                'referent_id' => $options['referent_id'] ?? null,
                'status' => 'failed',
                'error_code' => 'SYSTEM_ERROR',
                'error_message' => $e->getMessage(),
                'response_data' => json_encode(['error' => $e->getMessage()])
            ]);

            return [
                'success' => false,
                'message' => 'Gửi SMS thất bại: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Gọi API SMS thực tế
     */
    protected function callSmsApi($smsData)
    {
        // Ví dụ implementation cho một SMS provider
        try {
            $response = Http::timeout(30)->post($this->apiUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'phone' => $smsData['phone'],
                'message' => $smsData['message'],
                'sender' => $smsData['sender'],
                'type' => $smsData['type'],
                'unicode' => $smsData['use_unicode'],
                'request_id' => $smsData['request_id']
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => isset($data['status']) && $data['status'] === 'success',
                    'message' => $data['message'] ?? 'SMS sent successfully',
                    'provider_id' => $data['id'] ?? null,
                    'cost' => $data['cost'] ?? 0,
                    'raw_response' => $data
                ];
            } else {
                return [
                    'success' => false,
                    'error_code' => 'HTTP_' . $response->status(),
                    'error_message' => 'HTTP Error: ' . $response->status(),
                    'raw_response' => $response->body()
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error_code' => 'API_ERROR',
                'error_message' => $e->getMessage()
            ];
        }
    }

    /**
     * Phát hiện nhà mạng
     */
    protected function detectTelco($phone)
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove country code if exists
        if (substr($phone, 0, 2) === '84') {
            $phone = '0' . substr($phone, 2);
        }
        
        $prefix = substr($phone, 0, 3);
        
        // Viettel
        if (in_array($prefix, ['032', '033', '034', '035', '036', '037', '038', '039', '086', '096', '097', '098'])) {
            return 'VTE';
        }
        
        // VinaPhone
        if (in_array($prefix, ['088', '091', '094', '083', '084', '085', '081', '082'])) {
            return 'VNA';
        }
        
        // MobiFone
        if (in_array($prefix, ['089', '090', '093', '070', '079', '077', '076', '078'])) {
            return 'VMS';
        }
        
        return null;
    }

    /**
     * Gửi SMS hàng loạt
     */
    public function sendBulkSms($phones, $message, $options = [])
    {
        $results = [];
        $successCount = 0;
        $failedCount = 0;

        foreach ($phones as $phone) {
            $result = $this->sendSms(trim($phone), $message, $options);
            $results[] = $result;
            
            if ($result['success']) {
                $successCount++;
            } else {
                $failedCount++;
            }
        }

        return [
            'total' => count($phones),
            'success' => $successCount,
            'failed' => $failedCount,
            'results' => $results
        ];
    }

    /**
     * Lấy thống kê SMS
     */
    public function getStats($fromDate = null, $toDate = null)
    {
        $query = SmsHistory::query();
        
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
        
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        return [
            'total' => $query->count(),
            'success' => $query->where('status', 'success')->count(),
            'failed' => $query->where('status', 'failed')->count(),
            'by_type' => $query->groupBy('type')->selectRaw('type, count(*) as count')->pluck('count', 'type'),
            'by_telco' => $query->groupBy('telco')->selectRaw('telco, count(*) as count')->pluck('count', 'telco')
        ];
    }

    /**
     * Kiểm tra message có chứa ký tự unicode không
     */
    private function hasUnicodeChars($string)
    {
        return mb_strlen($string, 'UTF-8') !== strlen($string) || 
               preg_match('/[^\x00-\x7F]/', $string);
    }
}

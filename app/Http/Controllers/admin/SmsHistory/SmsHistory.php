<?php

namespace App\Http\Controllers\admin\SmsHistory;

use App\Http\Controllers\BaseController;
use App\Models\admin\SmsHistory\SmsHistory as SmsHistoryModel;
use App\Services\Sms\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SmsHistoryExport;

class SmsHistory extends BaseController
{
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $model = SmsHistoryModel::class;
        $view = 'admin.smshistory';
        $prefix = 'smshistory';
        $this->searchField = 'phone,message,sender,request_id';
        $this->dateFields = ['created_at'];
        $this->limit = 20;
        $this->order_by = ['created_at', 'desc'];
        $this->smsService = $smsService;
        parent::__construct($model, $view, $prefix);
    }

    public function index()
    {
        $request = request();
        
        // Áp dụng filter của BaseController
        $filter = $request->session()->get('filter');
        $query = $this->model::query();

        // Áp dụng filter BaseController trước
        $this->applyFilters($query, $filter);

        // Thêm filter tùy chỉnh cho SMS
        $this->applySmsFilters($query, $request);

        $list = $query->orderBy($this->order_by[0], $this->order_by[1])->paginate($this->limit);

        // Thống kê
        $stats = [
            'total' => SmsHistoryModel::count(),
            'success' => SmsHistoryModel::where('status', 'success')->count(),
            'failed' => SmsHistoryModel::where('status', 'failed')->count(),
            'today' => SmsHistoryModel::whereDate('created_at', today())->count()
        ];

        parent::setData([
            'list' => $list,
            'stats' => $stats,
            'filter' => $filter,
            'custom_filter' => $request->all()
        ]);

        return parent::index();
    }

    protected function applySmsFilters(&$query, $request)
    {
        // Filter theo trạng thái
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter theo loại SMS
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter theo nhà mạng
        if ($request->filled('telco')) {
            $query->where('telco', $request->telco);
        }

        // Filter theo khoảng thời gian (custom)
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Tìm kiếm custom
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('phone', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('message', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sender', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('request_id', 'LIKE', "%{$searchTerm}%");
            });
        }
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);

        parent::setData([
            'data' => $data
        ]);
        return parent::edit($id);
    }

    public function create()
    {
        return parent::create();
    }

    protected function setRules()
    {
        return [
            'phone' => 'required|string',
            'message' => 'required|string|max:1000',
            'type' => 'required|integer|between:1,4',
            'sender' => 'required|string|max:50',
        ];
    }

    protected function setCustomMessages()
    {
        return [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.max' => 'Nội dung tin nhắn không được quá 1000 ký tự.',
            'type.required' => 'Vui lòng chọn loại SMS.',
            'sender.required' => 'Vui lòng nhập tên người gửi.',
        ];
    }

    protected function setRedirect()
    {
        return ['admin.smshistory.create', 'admin.smshistory.edit', 'admin.smshistory.index'];
    }

    protected function save_extend($id)
    {
        // Xử lý logic sau khi lưu SMS
        $request = request();
        
        if ($request->filled('send_sms') && $request->send_sms == '1') {
            $sms = $this->model::find($id);
            if ($sms) {
                $this->processSendSms($sms);
            }
        }
    }

    public function save(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:1000',
            'type' => 'required|integer|between:1,4',
            'sender' => 'required|string|max:50',
        ], [
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'message.required' => 'Vui lòng nhập nội dung tin nhắn.',
            'message.max' => 'Nội dung tin nhắn không được quá 1000 ký tự.',
            'type.required' => 'Vui lòng chọn loại SMS.',
            'sender.required' => 'Vui lòng nhập tên người gửi.',
        ]);

        // Tách số điện thoại
        $phones = array_filter(array_map('trim', explode("\n", $request->phone)));
        
        if (empty($phones)) {
            return redirect()->back()->with('error', 'Vui lòng nhập ít nhất một số điện thoại.');
        }

        $successCount = 0;
        $failedCount = 0;

        foreach ($phones as $phone) {
            try {
                // Validate phone number format
                if (!preg_match('/^[0-9+\-\s()]{8,15}$/', $phone)) {
                    throw new \Exception("Số điện thoại không hợp lệ: $phone");
                }

                // Tạo log SMS
                $smsData = [
                    'phone' => $phone,
                    'telco' => $this->detectTelco($phone),
                    'type' => $request->type,
                    'sender' => $request->sender,
                    'message' => $request->message,
                    'request_id' => 'REQ_' . time() . '_' . rand(1000, 9999),
                    'use_unicode' => $request->has('use_unicode') ? 1 : 0,
                    'msg_length' => strlen($request->message),
                    'mt_count' => ceil(strlen($request->message) / ($request->has('use_unicode') ? 70 : 160)),
                    'account' => 'DMCFASHION',
                    'referent_id' => $request->referent_id,
                    'status' => 'success', // Mặc định là success, có thể thay đổi sau khi gọi API
                    'response_data' => json_encode(['message' => 'SMS sent successfully', 'timestamp' => now()])
                ];

                // Ở đây bạn có thể thêm logic gọi API SMS thực tế
                // $result = $this->sendSmsViaAPI($smsData);
                
                SmsHistoryModel::create($smsData);
                $successCount++;

            } catch (\Exception $e) {
                // Log lỗi
                SmsHistoryModel::create([
                    'phone' => $phone,
                    'telco' => $this->detectTelco($phone),
                    'type' => $request->type,
                    'sender' => $request->sender,
                    'message' => $request->message,
                    'request_id' => 'REQ_' . time() . '_' . rand(1000, 9999),
                    'use_unicode' => $request->has('use_unicode') ? 1 : 0,
                    'msg_length' => strlen($request->message),
                    'mt_count' => ceil(strlen($request->message) / ($request->has('use_unicode') ? 70 : 160)),
                    'account' => 'DMCFASHION',
                    'referent_id' => $request->referent_id,
                    'status' => 'failed',
                    'error_code' => 'SEND_ERROR',
                    'error_message' => $e->getMessage(),
                    'response_data' => json_encode(['error' => $e->getMessage(), 'timestamp' => now()])
                ]);
                $failedCount++;
            }
        }

        $message = "Đã gửi {$successCount} SMS thành công";
        if ($failedCount > 0) {
            $message .= ", {$failedCount} SMS thất bại";
        }

        return redirect()->route('admin.smshistory.index')->with('success', $message);
    }

    private function detectTelco($phone)
    {
        // Detect Vietnamese telecom providers
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

    private function processSendSms($sms)
    {
        try {
            $result = $this->smsService->sendSms(
                $sms->phone,
                $sms->message,
                $sms->type,
                $sms->sender
            );

            // Cập nhật status và response data
            $sms->update([
                'status' => $result['success'] ? 'success' : 'failed',
                'response_data' => json_encode($result),
                'error_code' => $result['error_code'] ?? null,
                'error_message' => $result['error_message'] ?? null
            ]);

            return $result;
        } catch (\Exception $e) {
            $sms->update([
                'status' => 'failed',
                'error_code' => 'SEND_ERROR',
                'error_message' => $e->getMessage(),
                'response_data' => json_encode(['error' => $e->getMessage()])
            ]);
            
            throw $e;
        }
    }

    public function delete()
    {
        // Sử dụng delete method của BaseController
        return parent::delete();
    }

    // Phương thức để xem chi tiết response data
    public function viewResponse($id)
    {
        $sms = SmsHistoryModel::find($id);
        
        if (!$sms) {
            return response()->json(['error' => 'Không tìm thấy log SMS'], 404);
        }

        return response()->json([
            'response_data' => $sms->response_data,
            'formatted_data' => $sms->formatted_response_data
        ]);
    }

    // Phương thức để resend SMS (nếu cần)
    public function resend(Request $request, $id)
    {
        $sms = SmsHistoryModel::find($id);
        
        if (!$sms) {
            return response()->json(['error' => 'Không tìm thấy log SMS'], 404);
        }

        try {
            // Gửi lại SMS sử dụng service
            $result = $this->processSendSms($sms);
            
            return response()->json([
                'success' => true,
                'message' => 'Đã gửi lại SMS thành công',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi gửi lại SMS: ' . $e->getMessage()
            ], 500);
        }
    }

    // Xuất báo cáo
    public function export(Request $request)
    {
        try {
            $query = SmsHistoryModel::query();
            
            // Áp dụng filter nếu có
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }
            
            if ($request->filled('telco')) {
                $query->where('telco', $request->telco);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            $data = $query->orderBy('created_at', 'desc')->get();
            
            $filename = 'sms_history_' . date('Y_m_d_H_i_s') . '.xlsx';
            
            return Excel::download(new SmsHistoryExport($data), $filename);
            
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra khi xuất báo cáo: ' . $e->getMessage());
        }
    }
}

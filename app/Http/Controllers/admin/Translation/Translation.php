<?php

namespace App\Http\Controllers\admin\Translation;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Translation\Translation as TranslationModel;
use Illuminate\Support\Facades\DB;

class Translation extends BaseController
{
    public function __construct()
    {
        $this->view = 'admin.translation';
        $this->model = TranslationModel::class;

    }

    public function saveKeyword()
    {
        $data = request()->all();
        $key = $data['key'];
        $text = $data['text'];
        $locale = 'en';
        DB::table('translations')->upsert([
            [
                'key' => $key,
                'text' => $text,
                'locale' => $locale,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ], ['key', 'locale'], ['text', 'updated_at']);
        return response()->json([
            'status' => 200,
            'message' => 'Lưu bản ghi thành công',
        ]);
    }

    public function deleteKeyword()
    {
        $data = request()->all();
        $key = $data['key'];
        $locale = 'en';
        DB::table('translations')->where('key', $key)->where('locale', $locale)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Xoá bản ghi thành công',
        ]);
    }
}

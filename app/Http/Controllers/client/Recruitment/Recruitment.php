<?php

namespace App\Http\Controllers\client\Recruitment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\Recruitment\Recruitment as RecruitmentModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Recruitment extends Controller
{
    public function index()
    {
        $list_job = RecruitmentModel::where('published', 1)->with('province', 'department')->get();

        $list_department = DB::table('departments')->where('published', 1)->get();
        $list_province_str = RecruitmentModel::select('province_id')->where('published', 1)->groupBy('province_id')->pluck('province_id');
        $list_province = DB::table('provinces')->whereIn('code', $list_province_str)->get();

        return view('client.recruitment.index', compact(['list_job', 'list_department', 'list_province']));
    }
    public function detail($id)
    {
        $job = RecruitmentModel::find($id);
        return view('client.recruitment.detail', compact('job'));
    }

    public function apply_recruitment(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $description = $request->input('description');
        $cv = $request->file('file');

        if ($cv) {
            $extension = $cv->getClientOriginalExtension();
            $allowedExtensions = ['pdf', 'doc', 'docx'];

            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(['message' => 'Định dạng file không hợp lệ. Chỉ chấp nhận các định dạng: pdf, doc, docx.'], 400);
            }

            $cvPath = $cv->store('cvs');
        } else {
            $cvPath = null;
        }

        $cvPath = $cv ? $cv->store('file') : null;

        DB::table('apply_recruitment')->insert([
            'recruitment_id' => $id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'description' => $description,
            'cv' => $cvPath,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('client.recruitment.detail', ['id' => $id], 302)->with(['message' => "Nộp đơn ứng tuyển thành công", 'status' => 'success']);
    }

    public function search()
    {
        $department_id = request()->input('department_id');
        $province_id = request()->input('province_id');
        $keyword = request()->input('keyword');

        $list_job = RecruitmentModel::where('published', 1)
            ->when($department_id, function ($query, $department_id) {
                return $query->where('department_id', $department_id);
            })
            ->when($province_id, function ($query, $province_id) {
                return $query->where('province_id', $province_id);
            })
            ->when($keyword, function ($query, $keyword) {
                return $query->where('name', 'like', "%$keyword%");
            })
            ->with('province', 'department')
            ->get();

        $list_department = DB::table('departments')->where('published', 1)->get();
        $list_province_str = RecruitmentModel::select('province_id')->where('published', 1)->groupBy('province_id')->pluck('province_id');
        $list_province = DB::table('provinces')->whereIn('code', $list_province_str)->get();

        return view('client.recruitment.search', compact('list_job', 'list_department', 'list_province', 'keyword', 'department_id', 'province_id'));
    }
}

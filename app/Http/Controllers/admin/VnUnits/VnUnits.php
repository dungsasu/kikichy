<?php

namespace App\Http\Controllers\admin\VnUnits;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\VnUnits\Province;
use App\Models\admin\VnUnits\District;
use App\Models\admin\VnUnits\Ward;

class VnUnits extends Controller
{
    public function index()
    {
        $provinces = Province::all();
        return response()->json($provinces);
    }

    public function getDistrictsByProvinceCode($province_code)
    {
        $districts = District::where('province_code', $province_code)->get();
        return response()->json($districts);
    }

    public function getWardsByDistrictCode($district_code)
    {
        $wards = Ward::where('district_code', $district_code)->get();
        return response()->json($wards);
    }

}

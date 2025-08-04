<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class Voucher extends Component
{

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $vouchers;

    public function __construct()
    {
        $this->vouchers = collect();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $year = date('Y');
        $member = Auth::guard('members')->user();

        if (@$member->ma_kh) {
            $customer = $member->ma_kh;
            $this->vouchers = DB::table('vouchers_' . $year)
                ->where('customer', $customer)
                ->where('date_expiration', '>=', date('Y-m-d'))
                ->where(function ($query) {
                    $query->where('used', null)->orWhere('used', 0);
                })->get();
        }
        return View::make('components.voucher')->with('vouchers', $this->vouchers);
    }
}

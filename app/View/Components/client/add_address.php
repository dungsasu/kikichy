<?php

namespace App\View\Components\client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\Component;

class add_address extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $cities = array();
    public function __construct($cities)
    {
        $this->cities = $cities;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $cities =  DB::table('provinces')->get();
        $member = Auth::guard('members')->user();
        
        return view('components.client.add_address',[
            'cities' => $cities,
            'member' =>  $member,
        ]);
    }
}
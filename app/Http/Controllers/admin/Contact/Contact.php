<?php

namespace App\Http\Controllers\admin\Contact;

use App\Http\Controllers\Controller;
use App\Models\admin\Contact\Contact as ContactModel;
use Illuminate\Http\Request;

class Contact extends Controller
{
    public function __construct()
    {
        $this->model = ContactModel::class;
        $this->view = 'admin.contact';
        $this->prefix = 'contact';
    }

    public function index()
    {
        $list = ContactModel::orderBy('id','asc')->get();
        //dd($list);
        parent::setData([
            'list' => $list,
        ]);
        return parent::index();
        
    }
    public function edit($id)
    {   
        $item_contact = ContactModel::where('id', $id)->orderBy('id','asc')->first();
        parent::setData([
            'item_contact' => $item_contact,
        ]);
        return parent::edit($id);
        
    }

}
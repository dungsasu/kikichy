<?php

namespace App\Http\Controllers\admin\User;

use App\Http\Controllers\BaseController;
use App\Models\admin\User\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\admin\Role\Role as RoleModel;


class User extends BaseController
{

    public function __construct()
    {
        parent::__construct(UserModel::class, 'admin.users', 'users');
    }

    public function index()
    {
        $list = $this->model::paginate(10);
        $list->map(function ($item) {
            $item->roles = $item->roles()->first();
        });
        parent::setData([
            'list' => $list
        ]);
        return parent::index();
    }

    public function create()
    {
        $maxOrdering = $this->model::max('ordering') + 1 ?? 1;
        $roles = RoleModel::where('published', 1)->get();
        return view($this->view . '.form', ['maxOrdering' => $maxOrdering, 'roles' => $roles]);
    }

    protected function setRedirect()
    {
        return ['admin.user.create', 'admin.user.edit'];
    }

    public function edit($id)
    {
        $data = $this->model::findOrFail($id);
        $data->roles = $data->roles()->get();
        $roles = RoleModel::where('published', 1)->get();
        return view($this->view . '.form', ['data' => $data, 'roles' => $roles]);
    }

    public function change_password(Request $request)
    {
        // Xác thực mật khẩu mới là bắt buộc
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới của bạn.',
        ]);

        // Lấy thông tin người dùng hiện tại
        $user = UserModel::where('id', $request->input('id'))->first();

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->get('password'));
        $user->save();

        // Chuyển hướng với thông báo thành công
        return redirect()->route('dashboard')->with('success', 'Mật khẩu đã được thay đổi thành công.');
    }
    public function save(Request $request)
    {
        if (!$request->input('id')) {
            $existingEmail = UserModel::where('email', $request->input('email'))->first();
            if ($existingEmail) {
                return redirect()->back()->withErrors('Email đã tồn tại');
            }
            $existingName = UserModel::where('username', $request->input('username'))->first();
            if ($existingName) {
                return redirect()->back()->withErrors('Tên đăng nhập đã tồn tại');
            }
            $rules['password'] = 'required';
            $customMessages['password.required'] = 'Vui lòng nhập mật khẩu';
        } else {
        }
        return parent::save($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        //安全性，其他人只能做的动作
        //未登录用户的限制
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function show (User $user)
    {
        return view('users.show' , compact('user'));
    }

    public function edit (User $user)
    {
        return view('users.edit' , compact('user'));
    }

    public function update (UserRequest $request , ImageUploadHandler $uploader , User $user)
    {
        $this->authorize('update', $user);//权限检测
        $data = $request->all();

        if ($request->avatar) {
            $result = $uploader->save($request->avatar , 'avatars' , $user->id , 362);
            if ($result) {
                $data['avatar'] = $result['path'];
            }
        }

        $user->update($data);
        return redirect()->route('users.show' , $user->id)->with('success' , '个人资料更新成功！');
    }
}

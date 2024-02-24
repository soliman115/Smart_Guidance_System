<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateInfoFormRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function update_info(UpdateInfoFormRequest $request)
    {

        $data=  $request->validated();
        $data['password'] = bcrypt($data['password']);
        if(request()->hasFile('image')){
            $file = request()->file('image');
            $name = time().rand(0,9999999999999). '_image.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/users'), $name);
            $data['image'] = $name;
        }
        $user_wanted = User::query()->where('id','=',request('id'))->first();
        if($user_wanted != null) {
            $user_wanted->update($data);
            return response()->json([
                'message'=>'user updated successfully',
                'status'=>200
            ]);
        }else{
            return response()->json([
                'message'=>'user not found',
                'status'=>400
            ],400);
        }
    }
}

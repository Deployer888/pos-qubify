<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Services\Role\RoleService;
use App\Services\User\UserService;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    use ApiReturnFormatTrait;

    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }
    public function update(Request $request)
    {
        $id = $request->user()->id;

        if($id == '' || $id == null){
            return $this->responseWithError(__('User not found'), [], 404);
        }

        $validator = Validator::make($request->all(), [
            'name'                  => ['required', 'string', 'max:100'],
            'email'                 => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($id)],
            'password'              => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string', 'min:8'],
            'phone'                 => ['nullable', 'max:25', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            //base64 image
            'avatar'                => ['nullable'],
            'role'                  => 'required',
            'status'                => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), [],422);
        }
        $data = $validator->validated();
      //  logger($data['avatar']);
        if ($user = $this->userService->createOrUpdate($data, $id)) {
            $data = [
                'id'            => $user->id,
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'role'          => optional($user->roles[0])->name,
                'status'        => $user->status,
                'avatar_url'    => $user->avatar_url,
            ];
            return $this->responseWithSuccess(__('Profile updated successfully'), $data, 201);
        } else {
            return $this->responseWithError(__('Unable to update profile, please try again'), [], 422);
        }

    }
    public function roles(){
        $roles = RoleResource::collection($this->roleService->get());
        return $this->responseWithSuccess(__('Roles'), $roles, 200);
    }

}

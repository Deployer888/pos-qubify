<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;

trait ApiReturnFormatTrait
{

    protected function responseWithSuccess($message = '', $data = [], $code = 200)
    {
        $response = [
            'status'        => true,
            'message'       => $message == '' ? __('Success') : $message,
            'data'          => $data,
        ];

        return response()->json($response, $code);

    }

    protected function responseWithError($message = '', $data = [], $code = null)
    {
        if ($code == null) {
            $code = 400;
        }
        $response = [
            'status'        => false,
            'message'       => $message == '' ? __('Success') : $message,
            'data'          => $data,
        ];

        return response()->json($response, $code);
    }
    protected function userCheck($user)
    {
        if(blank($user)):
            return $this->responseWithError(__('User not found.'),[],404);
        elseif($user->is_user_banned == 1) :
            return $this->responseWithError(__('Your account is banned.'),[],403);
        elseif($user->status == 0):
            return $this->responseWithError(__('Your account is inactive.'),[],403);
        endif;

        return 1;
    }
}

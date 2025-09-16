<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiReturnFormatTrait;
    protected $screen;

    public function __construct(){}

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email'     => 'required|max:190',
                'password'  => 'required|min:5|max:30',
            ]);

            if ($validator->fails()) {
                return $this->responseWithError(__('Please Check Email & password.Please try again'), [],422);
            }

            $user = User::where('email', $request->email)->first();

            if(blank($user)):
                return $this->responseWithError(__('User not found.'),[],404);
            elseif($user->status == User::STATUS_INACTIVE) :
                return $this->responseWithError(__('Your account is inactive.'),[],403);
            endif;

            if (!Hash::check($request->get('password'), $user->password)) {
                return $this->responseWithError(__('Invalid Credentials'), [],422);
            }

            $credentials = $request->only('email', 'password');
            try {
                if ($request['remember_me']) {
                    config(['jwt.ttl' => env('TOKEN_TTL_REMEMBER_ME',  86400 * 30)]);
                }
                if (!$token = JWTAuth::attempt($credentials)) {
                    return $this->responseWithError(__('Invalid credentials'), [], 401);
                }
            } catch (JWTException $e) {
                return $this->responseWithError(__('Unable to login, please try again'), [], 422);

            } catch (ThrottlingException $e) {
                return $this->responseWithError(__('Suspicious activity on your ip, try after').' '. $e->getDelay() .' '.  __('seconds'), [], 422);

            } catch (NotActivatedException $e) {
                return $this->responseWithError(__('Account is not activated. Verify your account first'),[],400);

            } catch (\Exception $e) {
                return $this->responseWithError(__('Something went wrong'), [], 500);
            }


            try {
                auth()->attempt($credentials, $request->has('remember'));
            } catch (NotActivatedException $e) {
                return $this->responseWithError(__('Your account is not verified.Please verify your account.'),[],403);
            }
            $data = [
                'token' => $token,
                'user' => $user,
            ];
            return $this->responseWithSuccess(__('Login successfully'), $data, 200);

        } catch (\Exception $e) {
            return $this->responseWithError(__($e->getMessage()), [],500);
        }
    }

    public function logout(Request $request)
    {
        try {
            Auth::logout();
            JWTAuth::invalidate(JWTAuth::getToken());
            return $this->responseWithSuccess(__('Logout Successfully'),[],200);
        } catch (JWTException $e) {
            JWTAuth::unsetToken();
            return $this->responseWithError(__('Failed to logout'), [], 500);
        }
    }
    public function loginUserDetails(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $this->responseWithSuccess(__('User Details'), $user, 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__($e->getMessage()), [],500);
        }
    }

}

<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\Customer\CustomerService;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\DropdownCustomerResource;



class CustomerAuthController extends Controller
{
    use ApiReturnFormatTrait;
    protected $screen;

    public function __construct()
    {

    }
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

            $user = Customer::where('email', $request->email)->first();

            if(blank($user)):
                return $this->responseWithError(__('User not found.'),[],404);
            elseif($user->status == Customer::STATUS_INACTIVE) :
                return $this->responseWithError(__('Your account is inactive.'),[],403);
            endif;

            if (!Hash::check($request->get('password'), $user->password)) {
                return $this->responseWithError(__('Invalid Credentials'), [],422);
            }
            $credentials = $request->only('email', 'password');
            try {
                if($request['remember_me']){
                    if (!$token = Auth::guard('api_customer')->setTTL(env('TOKEN_TTL_REMEMBER_ME',  86400 * 30))->attempt($credentials)) {
                        return $this->responseWithError(__('Invalid credentials'), [], 401);
                    }
                }else{
                    if (!$token = Auth::guard('api_customer')->attempt($credentials)) {
                        return $this->responseWithError(__('Invalid credentials'), [], 401);
                    }
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
                auth()->guard('customer')->attempt($credentials, $request->has('remember'));
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
    public function signup(Request $request, CustomerService $customerService)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name'                => ['required', 'max:100'],
                'last_name'                 => ['required', 'max:100'],
                'email'                     => ['required', 'email', 'max:100',  Rule::unique('customers')],
                'phone'                     => ['required', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
                'password'                  => ['required', 'string', 'min:8', 'confirmed'],
                'password_confirmation'     => ['required', 'string', 'min:8'],
                'company'                   => ['nullable', 'max:200'],
                'designation'               => ['nullable', 'max:200'],
                'address_line_1'            => ['nullable', 'max:255'],
                'address_line_2'            => ['nullable', 'max:255'],
                'country'                   => ['nullable', 'numeric'],
                'state'                     => ['nullable', 'numeric'],
                'city'                      => ['nullable', 'numeric'],
                'zipcode'                   => ['nullable', 'max:20'],
                'short_address'             => ['nullable', 'string', 'max:1000'],
                'billing_same'              => ['boolean', 'max:20'],
                'b_first_name'              => ['nullable', 'max:100'],
                'b_last_name'               => ['nullable', 'max:100'],
                'b_email'                   => ['nullable', 'email', 'max:100'],
                'b_phone'                   => ['nullable', 'max:20'],
                'b_address_line_1'          => ['nullable', 'max:255'],
                'b_address_line_2'          => ['nullable', 'max:255'],
                'b_country'                 => ['nullable', 'numeric'],
                'b_state'                   => ['nullable', 'numeric'],
                'b_city'                    => ['nullable', 'numeric'],
                'b_zipcode'                 => ['nullable', 'max:20'],
                'b_short_address'           => ['nullable', 'string', 'max:1000'],
                'avatar'                    => ['nullable', 'image', 'mimes:jpeg,jpg,png', 'max:300'],
                'status'                    => ['required', Rule::in([Customer::STATUS_ACTIVE, Customer::STATUS_INACTIVE])]

            ]);

            if ($validator->fails()) {
                return $this->responseWithError($validator->errors()->first(), [],422);
            }
            $data               = $validator->validated();

            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }
            if (\Route::current()->getName() == 'customer.auth.store.customer' || \Route::current()->getName() == 'api.customer.auth.store.customer') {
                $data['is_verified'] = Customer::STATUS_UNVERIFIED;
            }

            if ($customerService->createOrUpdateWithFile($data, 'avatar')) {
                if (\Route::current()->getName() == 'customer.auth.store.customer' || \Route::current()->getName() == 'api.customer.auth.store.customer') {
                    return $this->responseWithSuccess('Signup Successfully, Please Check your email for active your account.');
                }else{
                    return $this->responseWithSuccess('Customer created successfully', [], 200);
                }
            } else {
                return $this->responseWithError('Customer created failed.', [], 422);
            }


            return $this->responseWithSuccess('Signup Successfully, Please Check your email for active your account.');

        } catch (\Exception $e) {
            return $this->responseWithError(__('Something went wrong'),[],500);
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
            $user = auth()->guard('api_customer')->user();
            $data =[
                "id"                    => $user->id,
                "first_name"            => $user->first_name,
                "last_name"             => $user->last_name,
                "email"                 => $user->email,
                "phone"                 => $user->phone,
                "company"               => $user->company,
                "designation"           => $user->designation,
                "address_line_1"        => $user->address_line_1,
                "address_line_2"        => $user->address_line_2,
                "country"               => $user->country,
                "state"                 => $user->state,
                "city"                  => $user->city,
                "zipcode"               => $user->zipcode,
                "short_address"         => $user->short_address,
                "billing_same"          => $user->billing_same,
                "b_first_name"          => $user->b_first_name,
                "b_last_name"           => $user->b_last_name,
                "b_email"               => $user->b_email,
                "b_phone"               => $user->b_phone,
                "b_address_line_1"      => $user->b_address_line_1,
                "b_address_line_2"      => $user->b_address_line_2,
                "b_country"             => $user->b_country,
                "b_state"               => $user->b_state,
                "b_city"                => $user->b_city,
                "b_zipcode"             => $user->b_zipcode,
                "b_short_address"       => $user->b_short_address,
                "avatar"                => $user->avatar,
                "status"                => $user->status,
                "is_verified"           => $user->is_verified,
                "remember_token"        => $user->remember_token,
                "email_verified_at"     => $user->email_verified_at,
                "avatar_url"            => $user->avatar_url,
                "full_name"             => $user->full_name,
            ];
            return $this->responseWithSuccess(__('Login Customer Details'), $data, 200);
        } catch (\Exception $e) {
            return $this->responseWithError(__($e->getMessage()), [],500);
        }
    }
    public function index()
    {
        try {


            $customers = CustomerResource::collection(Customer::paginate(10))->response()->getData(true);
            return $this->responseWithSuccess('Customer List', $customers);
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }
    public function getDropdownCustomers()
    {
        try {


            $customers = DropdownCustomerResource::collection(Customer::all())->response()->getData(true);
            return $this->responseWithSuccess('Dropdown Customer List', $customers);
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }

}

<?php

namespace App\Http\Controllers\Customer\Auth;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\SystemCountry;
use App\Services\Customer\CustomerService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * login
     *
     * @return void
     */
    public function login()
    {
        return view('customer.auth.login');
    }
    public function registration()
    {
        $countries = SystemCountry::get();
        return view('customer.auth.registration',compact('countries'));
    }
    public function storeCustomer(CustomerRequest $request, CustomerService $customerService)
    {
        $data = $request->validated();

        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        if (\Route::current()->getName() == 'customer.auth.store.customer') {
            $data['is_verified'] = Customer::STATUS_UNVERIFIED;
        }

        if ($customerService->createOrUpdateWithFile($data, 'avatar')) {
            if (\Route::current()->getName() == 'customer.auth.store.customer') {
                flash(__('custom.account_create_successful_please_wait_for_admin_approval'))->success();
            }else{
                flash(__('custom.customer_create_successful'))->success();
            }
        } else {
            flash(__('custom.customer_create_failed'))->error();
        }

        return redirect()->route('customer.auth.login');
    }

    /**
     * loginSubmit
     *
     * @param  mixed $request
     * @return void
     */
    public function loginSubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials  = array('email' => $request->email, 'password' => $request->password);
        if (auth()->guard('customer')->attempt($credentials, $request->has('remember'))) {
            if(auth()->guard('customer')->user()->is_verified == Customer::STATUS_VERIFIED && auth()->guard('customer')->user()->status == Customer::STATUS_ACTIVE) {
                return  redirect()->route('customer.dashboard');
            }else{
                flash(__('custom.your_account_is_not_verified'))->error();
                Auth::guard('customer')->logout();
                return redirect()->route('customer.auth.login');
            }

        }elseif (auth()->attempt($credentials, $request->has('remember'))) {
            return  redirect()->route('admin.dashboard');
        }
        return redirect('/customer/login')
            ->withInput($request->only('email', 'remember'))
            ->withErrors([
                'email' => 'Incorrect email address or password',
            ]);
    }

    /**
     * logout
     *
     * @param  mixed $request
     * @return void
     */
    public function logout(Request $request)
    {
        if(Auth::guard('customer')->check())
        {
            Auth::guard('customer')->logout();
            return redirect()->route('customer.auth.login');
        }

        $this->guard()->logout();
        $request->session()->invalidate();

        return $this->loggedOut($request) ?: redirect('/');
    }
}

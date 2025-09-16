<?php

namespace App\Http\Controllers\Customer\Profile;

use App\Http\Requests\CustomerProfileRequest;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Models\SystemCountry;
use App\Services\Customer\CustomerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    protected $customerSearvice;

    /**
     * __construct
     *
     * @param  mixed $customerSearvice
     * @return void
     */
    public function __construct(CustomerService $customerSearvice)
    {
        $this->customerSearvice  = $customerSearvice;
    }
    public function profile()
    {
        $customer = $this->customerSearvice->get(Auth::guard('customer')->id());
        $countries = SystemCountry::get();

        set_page_meta(__('custom.edit_profile'));
        return view('customer.profile.profile', compact('customer','countries'));
    }

    /**
     * updateProfile
     *
     * @param  mixed $request
     * @param  mixed $profile
     * @return void
     */
    public function updateProfile(CustomerProfileRequest $request, $profile)
    {

        $data = $request->validated();

        if ($this->customerSearvice->createOrUpdateWithFile($data, 'avatar', Auth::guard('customer')->id())) {
            flash(__('custom.profile_update_successful'))->success();
        } else {
            flash(__('custom.profile_update_failed'))->error();
        }

        return redirect()->route('customer.dashboard');
    }
}

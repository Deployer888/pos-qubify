<?php

namespace App\Http\Controllers\Api\v100\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\RoleResource;
use App\Models\Customer;
use App\Services\Customer\CustomerService;
use App\Services\Role\RoleService;
use App\Services\User\UserService;
use App\Services\Utils\FileUploadService;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use DB;
use Illuminate\Validation\Rule;
use Image;
use File;

class ProfileController extends Controller
{
    use ApiReturnFormatTrait;

    protected $customerService;
    protected $fileUploadService;

    public function __construct(
        CustomerService $customerService,
        FileUploadService $fileUploadService)
    {
        $this->customerService = $customerService;
        $this->fileUploadService = $fileUploadService;
    }
    public function update(Request $request)
    {
        $user = $request->user();
        $id = $request->user()->id;

        if($id == '' || $id == null){
            return $this->responseWithError(__('Customer not found'), [], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name'                => ['required', 'max:100'],
            'last_name'                 => ['required', 'max:100'],
            'email'                     => ['required', 'email', 'max:100',  Rule::unique('customers')->ignore($id)],
            'phone'                     => ['required', 'max:20', 'regex:/^([0-9\s\-\+\(\)]*)$/'],
            'password'                  => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation'     => ['nullable', 'string', 'min:8'],
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
            'avatar'                    => ['nullable'],
            'status'                    => ['required', Rule::in([Customer::STATUS_ACTIVE, Customer::STATUS_INACTIVE])]
        ]);

        if ($validator->fails()) {
            return $this->responseWithError($validator->errors()->first(), [],422);
        }
        $data = $validator->validated();

        if (isset($data['avatar']) && $data['avatar'] != null) {
            if (strpos($data['avatar'], 'base64') !== false) {
                $data['avatar'] = $this->fileUploadService->uploadBase64($data['avatar'], Customer::FILE_STORE_PATH, $user->avatar);
            }else{
                $data['avatar'] = $this->fileUploadService->uploadFile($data['avatar'], Customer::FILE_STORE_PATH, $user->avatar);
            }
        }

        if ($customer = $this->customerService->createOrUpdate($data, $id)) {
            return $this->responseWithSuccess(__('Profile updated successfully'), $data, 201);
        } else {
            return $this->responseWithError(__('Unable to update profile, please try again'), [], 422);
        }

    }
}

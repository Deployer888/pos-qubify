<?php

namespace App\Http\Controllers\Api\v100\Admin;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiReturnFormatTrait;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\API\CustomerRequest;
use App\Services\Customer\CustomerService;
use App\Http\Resources\CustomerDetailsResource;

class CustomersController extends Controller
{
    protected $customerService;
    use ApiReturnFormatTrait;

    /**
     * __construct
     *
     * @param  mixed $customerService
     * @return void
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {

            $customers = CustomerResource::collection($this->customerService->all())->response()->getData(true);
            return $this->responseWithSuccess('Customer List', $customers);
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        try {
            $data = $request->validated();
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }
            $customer = $this->customerService->createOrUpdateWithFile($data, 'avatar');
            return $this->responseWithSuccess('Customer Created', new CustomerResource($customer));
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
            try{
                $customer_details = $this->customerService->customerShowDetails($customer);
                return $this->responseWithSuccess('Customer Details',new CustomerDetailsResource($customer_details));
            } catch(\Exception $e)
            {
                return $this->responseWithError('Something went wrong', $e->getMessage());

            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CustomerRequest $request, $id)
    {
        try {
            $data = $request->validated();
            if (isset($data['password']) && $data['password']) {
                $data['password'] = Hash::make($data['password']);
            }
            $this->customerService->createOrUpdateWithFile($data, 'avatar', $id);
            $customer = $this->customerService->get($id);
            return $this->responseWithSuccess('Customer Updated', new CustomerResource($customer));
        } catch (\Exception $e) {

            return $this->responseWithError('Something went wrong', $e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            if ($this->customerService->delete($id)) {
                return $this->responseWithSuccess(__('custom.customer_deleted_successful'),[],200);
            } else {
                return $this->responseWithError(__('custom.customer_deleted_failed'),[],500);
            }
        } catch (\Throwable $th) {
            return $this->responseWithError(__('custom.this_record_already_used'),[],403);
        }
    }
    public function verifyUnverify($id){
        try {
                $customer = $this->customerService->get($id);
                if (!$customer)
                {
                  return $this->responseWithError('Customer not found',[],500);

                }
                if($customer->is_verified == Customer::STATUS_VERIFIED){
                    $customer->is_verified = Customer::STATUS_UNVERIFIED;
                }else{
                    $customer->is_verified = Customer::STATUS_VERIFIED;
                }
                $customer->save();
            return $this->responseWithSuccess("Customer $customer->is_verified", new CustomerResource($customer));

        } catch (\Exception $e){
            return $this->responseWithError('Something went wrong',[],500);

        }


    }
}

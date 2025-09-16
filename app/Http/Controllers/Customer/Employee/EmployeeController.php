<?php

namespace App\Http\Controllers\Customer\Employee;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\SystemCountry;
use App\DataTables\EmployeeDataTable;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\CustomerRequest;
use App\Services\Employee\EmployeeService;

class EmployeeController extends Controller
{
    protected $employeeService;

    /**
     * __construct
     *
     * @param  mixed $employeeService
     * @return void
     */
    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(EmployeeDataTable $dataTable)
    {
        set_page_meta(__('custom.employee'));
        return $dataTable->render('customer.employee.index');;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // TODO: convert to service
        $countries = SystemCountry::get();

        set_page_meta(__('custom.add_customer'));
        return view('customer.employee.create', compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
        $data['type'] = Customer::TYPER_EMPLOYEE;
        $data['customer_id'] = Auth::guard('customer')->user()->id;
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }
        if ($this->employeeService->createOrUpdateWithFile($data, 'avatar')) {
            flash(__('custom.employee_create_successful'))->success();
        } else {
            flash(__('custom.employee_create_failed'))->error();
        }

        return redirect()->route('customer.employee.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        set_page_meta(__('custom.customer_details'));

        return view('customer.employee.show', $this->employeeService->customerShowDetails($customer));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $customer = $this->employeeService->get($id);
        if (!$customer) abort(404);

        $countries = SystemCountry::get();
        set_page_meta(__('custom.edit_employee'));
        return view('customer.employee.edit', compact('customer', 'countries'));
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
        $data = $request->validated();
        $data['type'] = Customer::TYPER_EMPLOYEE;
        $data['customer_id'] = Auth::guard('customer')->user()->id;
        if (!isset($data['billing_same'])) {
            $data['billing_same'] = 0;
        }
        if (isset($data['password']) && $data['password']) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($this->employeeService->createOrUpdateWithFile($data, 'avatar', $id)) {
            flash(__('custom.employee_updated_successful'))->success();
        } else {
            flash(__('custom.employee_updated_failed'))->error();
        }

        return redirect()->route('customer.employee.index');
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
            if ($this->employeeService->delete($id)) {
                flash(__('custom.employee_deleted_successful'))->success();
            } else {
                flash(__('custom.employee_deleted_failed'))->error();
            }
        } catch (\Throwable $th) {
            flash(__('custom.this_record_already_used'))->warning();
        }

        return redirect()->route('admin.employee.index');
    }
    public function verifyUnverify($id)
    {
        $customer = $this->employeeService->get($id);
        if (!$customer) abort(404);
        if ($customer->is_verified == Customer::STATUS_VERIFIED) {
            $customer->is_verified = Customer::STATUS_UNVERIFIED;
        } else {
            $customer->is_verified = Customer::STATUS_VERIFIED;
        }
        $customer->save();
        return redirect()->route('customer.employee.index');
    }
}

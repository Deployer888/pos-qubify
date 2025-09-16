<?php

namespace App\Services\Employee;


use App\Models\Invoice;
use App\Services\BaseService;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

/**
 * CustomerService
 */
class EmployeeService extends BaseService
{
    /**
     * __construct
     *
     * @param  mixed $model
     * @return void
     */
    public function __construct(Customer $model)
    {
        parent::__construct($model);
    }

    public function customerShowDetails($customer)
    {
        $invoices = Invoice::query()
            ->with('items', 'payments', 'customerInfo')
            ->latest('date')
            ->where('customer_id', $customer->id);

        return [
            'customer' => $customer,
            'invoices' => $invoices->simplePaginate(100),
            'products' => $this->products($invoices->get()),
            'not_paid_invoices' => $invoices->where('status', '!=', 'paid')->simplePaginate(100),
        ];
    }

    public function products($invoices)
    {
        $products = [];
        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                $products[] = [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ];
            }
        }

        return $products;
    }

    public function updateProfile(array $data, $id)
    {
        try {
            // Update
            $user = $this->get($id);

            // Password
            if (isset($data['password']) && $data['password']) {
                $user->password = Hash::make($data['password']);
            }

            // Avatar
            if (isset($data['avatar']) && $data['avatar'] != null) {
                $user->avatar = $this->uploadFile($data['avatar'], $user->avatar);
            }

            $user->first_name   = $data['first_name'];
            $user->last_name    = $data['last_name'];
            $user->phone        = $data['phone'];
            $user->email        = $data['email'];

            // Update user
            return $user->save();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function all()
    {
        return $this->model->orderBy('first_name', 'asc')->paginate(10);
    }
}

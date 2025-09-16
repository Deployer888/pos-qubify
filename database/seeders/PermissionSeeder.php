<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'Depot' => [
                'Show Depot List',
                'Add Depot',
                'Edit Depot',
                'Delete Depot',
                'Manage Depot Stock',
                'Manage Depot Customers',
                'Import Depot Customers',
                'Access Depot POS',
                'View Sales History'
            ],
            'Dashboard' => [
//                'Show Dashboard',
                'Total Customer',
                'Total Supplier',
                'Total Product',
                'Total Sale',
                'Total Purchase',
                'Total Expenses',
                'Total Sale Amount',
                'Total purchase Amount',
                'Total Expenses Amount',
                'Total Product Category',
//                'total_product_with_variant',
                'Total Sale Return Request',
                'Total Pending Sale Return Request',
                'Total Stock',
                'Total Invoice By Auth User',
                'Total Sale By Auth User',

                'Total Warehouse',
                'Active Coupons',
                'Total Sale Return',

                'Sale Report Charts',
                'Top Products',
                'Best Items',
                'Latest Sales',

            ],
            'User' => [
                'Add User',
                'Edit User',
                'Show User',
                'List User',
                'Delete User'
            ],
            'Role' => [
                'Add Role',
                'Edit Role',
                'Show Role',
                'List Role',
                'Delete Role'
            ],
            'Product' => [
                'Add Product',
                'Edit Product',
                'Stock Product',
                'List Product',
                'Delete Product'
            ],
            'Warehouse' => [
                'Add Warehouse',
                'Edit Warehouse',
                'Show Warehouse',
                'List Warehouse',
                'Delete Warehouse'
            ],
            'Product Category' => [
                'Add Product Category',
                'Edit Product Category',
                'List Product Category',
                'Delete Product Category'
            ],
            'Brand' => [
                'Add Brand',
                'Edit Brand',
                'List Brand',
                'Delete Brand'
            ],
            'Manufacturer' => [
                'Add Manufacturer',
                'Edit Manufacturer',
                'List Manufacturer',
                'Delete Manufacturer'
            ],
            'Weight Unit' => [
                'Add Weight Unit',
                'Edit Weight Unit',
                'List Weight Unit',
                'Delete Weight Unit'
            ],
            'Measurement Unit' => [
                'Add Measurement Unit',
                'Edit Measurement Unit',
                'List Measurement Unit',
                'Delete Measurement Unit'
            ],
            'Attribute' => [
                'Add Attribute',
                'Edit Attribute',
                'List Attribute',
                'Delete Attribute'
            ],
            'Purchase' => [
                'Add Purchase',
                'Edit Purchase',
                'Show Purchase',
                'List Purchase',
                'Cancel Purchase',
                'Receive Purchase',
                'Confirm Purchase',
                'Return Purchase',
                'Delete Purchase',
                'Purchase Receive List',
                'Purchase Return List'
            ],
            'Coupon' => [
                'Add Coupon',
                'Edit Coupon',
                'List Coupon',
                'Delete Coupon'
            ],
//            'campaign' => [
//                'Add Campaign',
//                'Edit Campaign',
//                'List Campaign',
//                'Delete Campaign'
//            ],
            'Customer' => [
                'Add Customer',
                'Edit Customer',
                'List Customer',
                'Delete Customer',
                'Verify Customer'
            ],
            'Supplier' => [
                'Add Supplier',
                'Edit Supplier',
                'List Supplier',
                'Delete Supplier'
            ],
            'Expenses Category' => [
                'Add Expenses Category',
                'Edit Expenses Category',
                'List Expenses Category',
                'Delete Expenses Category'
            ],
            'Expenses' => [
                'Add Expenses',
                'Edit Expenses',
                'Show Expenses',
                'List Expenses',
                'Delete Expenses'
            ],

            'Invoice' => [
                'List Invoice',
                'Add Invoice',
                'Edit Invoice',
                'Show Invoice',
                'Return Invoice',
                'View Payment Invoice',
                'Make Payment Invoice',
                'Download Invoice',
                'Send Invoice',
                'Link Invoice',
                'Delete Invoice'
            ],
            'Sale Return' => [
                'Show Sale Return',
                'Return Sale Return',
                'Sale Return List',
                'Sale Return Request List'
            ],
            'Reports' => [
                'Expenses Report',
                'Expired Products Report',
                'Sales Report',
                'Purchases Report',
                'Payments Report',
                'Warehouse Selling Price Report',
            ],
            'Settings' => [
                'Site Settings'
            ]



        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        foreach ($permissions as $parent => $child) {
            $parent_data = \App\Models\Permission::create([
                'name' => $parent,
                'guard_name' => 'web'
            ]);

            foreach ($child as $c) {
                \App\Models\Permission::create([
                    'name' => $c,
                    'guard_name' => 'web',
                    'parent_id' => $parent_data->id
                ]);
            }
        }
    }
}

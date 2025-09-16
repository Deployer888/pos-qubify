<?php

namespace App\Imports;

use Exception;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Manufacturer;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use App\Traits\HasImageImporter;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Services\Product\ProductService;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Services\Product\ProductStockService;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow
{
    use Importable, HasImageImporter;

    protected $productService;
    protected $productStockService;

    public function __construct(ProductService $productService, ProductStockService $productStockService)
    {
        $this->productService = $productService;
        $this->productStockService = $productStockService;
    }

    public function collection(Collection $rows)
    {
        $products = [];
        $rowNumber = 2; // heading is row 1
        $errors = [];

        foreach ($rows as $row) {
            $rowArray = $row->toArray();

            // Validate row
            $validator = Validator::make($rowArray, $this->rules(), $this->customValidationMessages());

            if ($validator->fails()) {
                $errorMsgs = $validator->errors()->all();
                $errors[] = "Row {$rowNumber}: " . implode(', ', $errorMsgs);
            }

            $rowNumber++;
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }

        // If validation passes for all rows, then insert
        $rowNumber = 2;
        foreach ($rows as $row) {
            $rowArray = $row->toArray();

        // 🔹 Category
        $categoryName = trim($rowArray['category']);
        $category = ProductCategory::whereRaw('LOWER(name) = ?', [strtolower($categoryName)])->first();
        if (!$category) {
            $category = ProductCategory::create([
                'name' => $categoryName,
                'status' => ProductCategory::STATUS_ACTIVE,
                'created_by' => Auth::id()
            ]);
        }
        $category_id = $category->id;

        // 🔹 Brand
        $brand_id = null;
        $brandName = trim($rowArray['brand'] ?? '');
        if ($brandName) {
            $brand = Brand::whereRaw('LOWER(name) = ?', [strtolower($brandName)])->first();
            if (!$brand) {
                $brand = Brand::create([
                    'name' => $brandName,
                    'status' => Brand::STATUS_ACTIVE,
                    'created_by' => Auth::id()
                ]);
            }
            $brand_id = $brand->id;
        }

        // 🔹 Manufacturer
        $manufacturer_id = null;
        $manufacturerName = trim($rowArray['manufacturer'] ?? '');
        if ($manufacturerName) {
            $manufacturer = Manufacturer::whereRaw('LOWER(name) = ?', [strtolower($manufacturerName)])->first();
            if (!$manufacturer) {
                $manufacturer = Manufacturer::create([
                    'name' => $manufacturerName,
                    'status' => Manufacturer::STATUS_ACTIVE,
                    'created_by' => Auth::id()
                ]);
            }
            $manufacturer_id = $manufacturer->id;
        }

            $imagePath = !empty($rowArray['thumb'])
                ? $this->importImage($rowArray['thumb'], null, 'products')
                : null;

            $barcode = $rowArray['barcode'] ?? null;
            $sku = $rowArray['sku'] ?? null;
            $barcodeImage = ($barcode && $sku)
                ? $this->productService->processBarcodeImage($sku, $barcode, $rowArray['barcode_image'])
                : null;

           $product = Product::create([
                'name' => $rowArray['name'],
                'sku' => $sku,
                'barcode' => $barcode,
                'barcode_image' => $barcodeImage,
                'category_id' => $category_id,
                'brand_id' => $brand_id,
                'manufacturer_id' => $manufacturer_id,
                'model' => $rowArray['model'] ?? null,
                'price' => $rowArray['price'],
                'customer_buying_price' => $rowArray['customer_buying_price'] ?? null,
                'weight' => $rowArray['weight'] ?? null,
                'weight_unit' => $rowArray['weight_unit'] ?? null,
                'dimension_l' => $rowArray['dimension_l'] ?? null,
                'dimension_w' => $rowArray['dimension_w'] ?? null,
                'dimension_d' => $rowArray['dimension_d'] ?? null,
                'measurement_unit' => $rowArray['measurement_unit'] ?? null,
                'thumb' => $imagePath,
                'notes' => $rowArray['notes'] ?? null,
                'desc' => $rowArray['desc'] ?? null,
                'is_variant' => !empty($rowArray['is_variant']),
                'is_batch_product' => !empty($rowArray['is_batch_product']),
                'status' => $rowArray['status'] ?? Product::STATUS_ACTIVE,
                'available_for' => $rowArray['available_for'] ?? null,
                'tax_status' => $rowArray['tax_status'] ?? null,
                'custom_tax' => $rowArray['custom_tax'] ?? null,
                'split_sale' => !empty($rowArray['split_sale']),
                'stock' => $rowArray['stock'] ?? 0,
                'created_by' => auth()->id(),
            ]);
            $defaultWarehouse = Warehouse::where('is_default', 1)->first();
            if ($defaultWarehouse) {
                $stock = [
                    "is_variant" => "0",
                    "alert_quantity" => "",
                    "warehouse_stock" => [
                        [
                            "warehouse" => $defaultWarehouse->id,
                            "stock" => "0",
                            "quantity" => "0",
                            "price" => "0",
                            "customer_buying_price" => "0",
                            "adjust_type" => null
                        ]
                    ],
                    "supplier_id" => "1"
                ];
                $this->productStockService->normalStockUpdate($stock, $product->id);
            } else {
                flash(__('custom.default_warehouse_not_found'))->error();
            }

            $rowNumber++;
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:200', Rule::unique('products', 'name')],
            'sku' => ['required', 'max:100', Rule::unique('products', 'sku')],
            'barcode' => ['required', 'max:100', Rule::unique('products', 'barcode')],
            'price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'category' => ['required', 'string'],
            'brand' => ['nullable', 'string'],
            'manufacturer' => ['nullable', 'string'],
            'thumb' => ['nullable', 'string'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'name.required' => 'Product name is required.',
            'name.unique' => 'The product name ":input" already exists.',
            'sku.required' => 'SKU is required.',
            'sku.unique' => 'The SKU ":input" already exists.',
            'barcode.required' => 'Barcode is required.',
            'barcode.unique' => 'The barcode ":input" already exists.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'status.required' => 'Status is required.',
            'status.in' => 'Invalid status value. Allowed: active, inactive.',
            'category.required' => 'Category name is required.',
        ];
    }
}

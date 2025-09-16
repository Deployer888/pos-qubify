<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('custom.warehouse_details') }}</title>
    <style>
        body {
            font-family: "Roboto", sans-serif
        }
    </style>
</head>

<body>
    <div style="width: 100%; text-align: center">
        <p>
            <b style="font-size: 26px">{{ $warehouse_details->name }}</b>
            <br>
            {{ __t('email') }}: {{ $warehouse_details->email }}
            <br>
            {{ __t('phone') }}: {{ $warehouse_details->phone }}
            <br>
            {{ __t('company') }}: {{ $warehouse_details->company_name }}
            <br>
            {{ __t('address') }}: {{ $warehouse_details->address_1 }} <br>
            {{ $warehouse_details->address_2 }}
        </p>
    </div>

    <div style="width: 100%;">
        <table border="1" cellpadding="2" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><strong>{{ __('custom.product') }}</strong></th>
                    <th><strong>{{ __('custom.sku') }}</strong></th>
                    <th><strong>{{ __('custom.category') }}</strong></th>
                    <th>
                        <stong>{{ __('custom.manufacturer') }}</stong>
                    </th>
                    <th><strong>{{ __('custom.quantity') }}</strong></th>
                    <th><strong>{{ __('custom.price') }}</strong></th>
                    <th><strong>{{ __('custom.total') }}</strong></th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalStock = 0;
                    $totalAmout = 0;
                @endphp
                @foreach ($warehouse_details->product_stocks as $product_stocks)
                    <tr>
                        <td class="text-left">
                            {{ optional($product_stocks->product)->name }}
                        </td>
                        <td>{{ optional($product_stocks->product)->sku }}</td>
                        <td>{{ optional(optional($product_stocks->product)->category)->name }}</td>
                        <td>{{ optional(optional($product_stocks->product)->manufacturer)->name }}</td>
                        <td>
                            @if ($product_stocks->quantity <= 0)
                                <span class="text-danger"><b>{{ $product_stocks->quantity }}</b></span>
                                {{ optional(optional($product_stocks->product)->weight_unit)->name }}
                            @else
                                <span class="text-success"><b>{{ $product_stocks->quantity }}</b></span>
                                {{ optional(optional($product_stocks->product)->weight_unit)->name }}
                            @endif
                        </td>
                        <td class="text-right">
                            {{ currencySymbol() . make2decimal(optional($product_stocks->product)->price) }}</td>
                        <td class="text-right">
                            {{ currencySymbol() . optional($product_stocks->product)->price * $product_stocks->quantity }}
                        </td>
                    </tr>
                    @php
                        $totalStock += $product_stocks->quantity;
                        $totalAmout += optional($product_stocks->product)->price * $product_stocks->quantity;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">{{ __('custom.total') }}</th>
                    <th>{{ $totalStock }}</th>
                    <th></th>
                    <th>{{ currencySymbol() . $totalAmout }}</th>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>

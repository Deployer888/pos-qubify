<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ static_asset('admin/css/pdf-style.css') }}" rel="stylesheet" type="text/css" />
    <title>{{ __('custom.export') }}</title>
</head>

<body>
    <p><b>{{ __('custom.expired_product_report') }}:</b> {{ $report_range ?? '' }}</p>
    <p><b>{{ __('custom.total_quantity') }}:</b> {{ $expiredProductStocks->sum('quantity') }}</p>

    <table class="ic-main-table" width="100%" cellpadding="0" cellspacing="0" border="1">
        <thead>
            <tr>
                <th class="ic-table-td">{{ __('custom.sl') }}SL#</th>
                <th class="ic-table-td">{{ __('custom.product') }}</th>
                <th class="ic-table-td">{{ __('custom.warehouse') }}</th>
                <th class="ic-table-td">{{ __('custom.batch') }}</th>
                <th class="ic-table-td">{{ __('custom.expiry_date') }}</th>
                <th class="ic-table-td">{{ __('custom.quantity') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($expiredProductStocks as $stock)
                <tr>
                    <td class="ic-table-td">{{ $loop->iteration }}</td>
                    <td class="ic-table-td">{{ $stock->product?->name }}</td>
                    <td class="ic-table-td">{{ $stock->warehouse?->name }}</td>
                    <td class="ic-table-td">{{ $stock->batch }}</td>
                    <td class="ic-table-td">{{ $stock->expiry_date }}</td>
                    <td class="ic-table-td">{{ $stock->quantity }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="ic-table-td text-right"><strong>{{ __('custom.total') }}</strong></td>
                <td class="ic-table-td"><strong>{{ $expiredProductStocks->sum('quantity') }}</strong></td>
            </tr>
        </tfoot>
    </table>
</body>

</html>

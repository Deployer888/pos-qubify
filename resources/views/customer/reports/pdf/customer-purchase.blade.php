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
    <p><b>{{ __('custom.sales_report') }}:</b> {{ $report_range ?? '' }}</p>
    <p><b>{{ __('custom.gross_total') }}:</b> {{ currencySymbol().make2decimal($gross_total ?? 0) }}</p>
    <p><b>{{ __('custom.net_total') }}:</b> {{ currencySymbol().make2decimal($total_paid ?? 0) }}</p>
    <p><b>{{ __('custom.due') }}:</b> {{ currencySymbol().make2decimal($gross_total - $total_paid) }}</p>
    <table class="ic-main-table" width="100%" cellpadding=" 0" cellspacing="0" border="1" >
        <tr>
            <th class="ic-table-td">{{ __('custom.sl') }}SL#</th>
            <th class="ic-table-td">{{ __('custom.invoice_id') }}</th>
            <th class="ic-table-td" width="10%">{{ __('custom.date') }}</th>
            <th class="ic-table-td">{{ __('custom.customer') }}</th>
            <th class="ic-table-td">{{ __('custom.tax') }}</th>
            <th class="ic-table-td">{{ __('custom.discount') }}</th>
            <th class="ic-table-td">{{ __('custom.total') }}</th>
            <th class="ic-table-td">{{ __('custom.due') }}</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td class="ic-table-td">{{ ++$loop->index }}</td>
            <td class="ic-table-td">{{ make8digits($item->id) }}</td>
            <td class="ic-table-td">{{ custom_date($item->date) }}</td>
            <td class="ic-table-td">
                @if ($item->customer_id)
                {{ucfirst($item->customer['full_name'] ?? '')}}
                @else
                {{ucfirst($item->customer['full_name'] ?? 'Walk-In Customer')}}
                @endif
            </td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->tax_amount) }}</td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->discount_amount) }}</td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->total) }}</td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->total - $item->total_paid) }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>

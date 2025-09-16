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
    <p class="mb-0"><b>{{ __('custom.purchases_report') }}:</b> {{ $report_range ?? '' }}</p>
    <p><b>{{ __('custom.total') }}:</b> {{ currencySymbol().make2decimal($total ?? 0) }}</p>
    <table class="ic-main-table" cellpadding="0" cellspacing="0" border="1" width="100%">
        <tr>
            <th class="ic-table-td">{{ __('custom.sl') }}#</th>
            <th class="ic-table-td">{{ __('custom.purchase_number') }}</th>
            <th class="ic-table-td" width="10%">{{ __('custom.date') }}</th>
            <th class="ic-table-td" width="10%">{{ __('custom.supplier') }}</th>
            <th class="ic-table-td" width="10%">{{ __('custom.total') }}</th>
            <th class="ic-table-td" width="30%">{{ __('Custom.notes') }}</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td class="ic-table-td">{{ ++$loop->index }}</td>
            <td class="ic-table-td">{{ $item->purchase_number }}</td>
            <td class="ic-table-td">{{ custom_date($item->date) }}</td>
            <td class="ic-table-td">{{ $item->supplier->first_name ?? '' }}</td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->total) }}</td>
            <td class="ic-table-td">{{ $item->notes }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>

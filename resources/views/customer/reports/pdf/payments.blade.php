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
    <p class="mb-0"><b>{{ __('custom.payments_report') }}:</b> {{ $report_range ?? '' }}</p>
    <p><b>{{ __('custom.total') }}:</b> ${{ currencySymbol().make2decimal($total ?? 0) }}</p>
    <table class="ic-main-table" cellpadding="0" cellspacing="0" border="1" width="100%">
        <tr>
            <th class="ic-table-td">{{ __('custom.sl') }}#</th>
            <th class="ic-table-td">{{ __('custom.invoice_id') }}</th>
            <th class="ic-table-td">{{ __('custom.date') }}</th>
            <th class="ic-table-td">{{ __('custom.payment_type') }}</th>
            <th class="ic-table-td">{{ __('custom.total') }}</th>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td class="ic-table-td">{{ ++$loop->index }}</td>
            <td class="ic-table-td">{{ make8digits($item->invoice_id) }}</td>
            <td class="ic-table-td">{{ $item->date }}</td>
            <td class="ic-table-td">{{ $item->payment_type }}</td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->amount) }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>

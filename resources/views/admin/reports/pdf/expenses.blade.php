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
    <p class="mb-0"><b>{{ __('custom.expenses_report') }}:</b> {{ $report_range ?? '' }}</p>
    <p><b>{{ __('custom.total') }}:</b> {{ currencySymbol().make2decimal($total ?? 0) }}</p>
    <table class="ic-main-table" cellpadding="0" cellspacing="0" border="1" width="100%">
        <tr>
            <td class="ic-table-td"><b>{{ __('custom.sl') }}#</b></td>
            <td class="ic-table-td ic-custom-date"><b>{{ __('custom.date') }}</b></td>
            <td class="ic-table-td ic-custom-title"><b>{{ __('custom.title') }}</b></td>
            <td class="ic-table-td"><b>{{ __('custom.category') }}</b></td>
            <td class="ic-table-td"><b>{{ __('custom.total') }}</b></td>
            <td class="ic-table-td"><b>{{ __('custom.notes') }}</b></td>
        </tr>
        @foreach ($data as $item)
        <tr>
            <td class="ic-table-td">{{ ++$loop->index }}</td>
            <td class="ic-table-td">{{ $item->date }}</td>
            <td class="ic-table-td">{{ $item->title }}</td>
            <td class="ic-table-td">{{ $item->category->name ?? '' }}</td>
            <td class="ic-table-td">{{ currencySymbol().make2decimal($item->total) }}</td>
            <td class="ic-table-td">{{ $item->notes }}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>

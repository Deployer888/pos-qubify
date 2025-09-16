<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{__('custom.print')}}</title>
    <meta charset="UTF-8">
    <meta name=description content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="/admin/plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/admin/plugins/bootstrap/font-awesome.min.css">
    <style>
        body {
            margin: 20px
        }

        .img-64 {
            width: 64px !important;
        }
    </style>
</head>

<body>
    <table class="table table-bordered table-condensed table-striped">
        @foreach($data as $row)
        @if ($loop->first)
        <tr>
            @foreach($row as $key => $value)
            <th>{!! $key !!}</th>
            @endforeach
        </tr>
        @endif
        <tr>
            @foreach($row as $key => $value)
            @if(is_string($value) || is_numeric($value))
            <td>{!! $value !!}</td>
            @else
            <td></td>
            @endif
            @endforeach
        </tr>
        @endforeach
    </table>
</body>

</html>

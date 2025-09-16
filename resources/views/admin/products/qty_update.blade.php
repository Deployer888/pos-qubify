<form class="form-validate" action="{{ route('admin.product-stocks.update-by-stock', $product->id) }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    {{--    <div class="row">--}}
    @if ($product->is_variant)
        @php
            $warehouses = $product->allStock->groupBy('warehouse_id');
            // dd($warehouses,'test');
        @endphp
        <input type="hidden" name="is_variant" value="1">
        <table class="table table-responsive table-bordered content-center">
            <thead>
            <tr>
                <th>{{ __('custom.warehouse') }}</th>
                <th>{{ __('custom.quantity') }}</th>

            </tr>
            </thead>
            <tbody>
            @foreach($warehouses as $warehouse)
                <tr>
                    <td>
                        {{ \App\Models\Warehouse::find($warehouse[0]->warehouse_id)->name }}

                    </td>
                    <td>
                        <table class="table table-responsive table-bordered content-center">
                            @foreach($warehouse as $stock)
                            <tr>
                                <td>
                                    {{ @$stock->attribute->name }} : {{ @$stock->attributeItem->name }}
                                </td>
                                <td>
                                    <input type="hidden" name="stock_id[]" class="form-control" value="{{ @$stock->id }}">
                                    <input type="number" name="quantity[]" class="form-control" value="{{ @$stock->quantity }}">
                                </td>
                            </tr>
                            @endforeach
                        </table>
                    </td>
{{--                    <td>--}}
{{--                        {{ @$stock->attribute->name }} : {{ @$stock->attributeItem->name }}--}}
{{--                    </td>--}}
{{--                    <td>--}}
{{--                        <input type="hidden" name="stock_id[]" class="form-control" value="{{ @$stock->id }}">--}}
{{--                        <input type="number" name="quantity[]" class="form-control" value="{{ @$stock->quantity }}">--}}
{{--                    </td>--}}
                </tr>
            @endforeach
        </table>
    @else
        <input type="hidden" name="is_variant" value="0">
        <table class="table table-responsive table-bordered content-center">
            <thead>
            <tr>
                <th>{{ __('custom.warehouse') }}</th>
                <th>{{ __('custom.quantity') }}</th>

            </tr>
            </thead>
            <tbody>

            @foreach($product->allStock as $stock)
                <tr>
                    <td>
                        {{ @$stock->warehouse->name }}
                    </td>
                    <td>
                        <input type="hidden" name="stock_id[]" class="form-control" value="{{ @$stock->id }}">
                        <input type="number" name="quantity[]" class="form-control" value="{{ @$stock->quantity }}">
                    </td>
                </tr>
            @endforeach
        </table>

    @endif
    {{--    </div>--}}
    <div class=" form-group float-right">
        <div>
            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
            </button>
            <a class="btn btn-danger waves-effect" href="{{ route('admin.products.index') }}">
                <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
            </a>
        </div>
    </div>
</form>

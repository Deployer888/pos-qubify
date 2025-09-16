@php
    $position = null;
    for ($i=0; $i < $sub_category->position; $i++){
        $position .= '-';
    }
@endphp
<option value="{{ $sub_category->id }}"
        {{ @$product_category ? ($sub_category->id == $product_category->parent_id ? 'selected': '') : (@$product ? ($sub_category->id == $product->category_id) ? 'selected': '' : '')}}
>
    {{ $position."-".$sub_category->name }}</option>
@if ($sub_category->subCategory)
     @foreach ($sub_category->subCategory as $childCategory)
         @include('admin.product_categories.child-categories', ['sub_category' => $childCategory])
     @endforeach
@endif

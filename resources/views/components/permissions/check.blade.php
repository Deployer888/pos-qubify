@if(auth()->user()->hasRole('Super Admin') || auth()->user()->can($permission))
    {{ $slot }}
@endif
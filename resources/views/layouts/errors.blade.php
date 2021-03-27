{{-- @section('errors-box')
@if (@yield("errorsAny") || isset(@yield("error")))
<div class="alert alert-danger">
    @if (@yield("errorsAny"))
        <ul>
            @foreach (@yield("errorsAll") as $message)
                <li>{{ $message }}</li>
            @endforeach
        </ul>
    @endif
    @if (isset(@yield("error")))
       @yield("error")
    @endif
</div>
@endif
@endsection --}}
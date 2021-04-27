<div class="container">
    @if (session()->has('error') || isset($error))
    <div class="alert alert-danger">
        {{ session()->get('error') ?? $error }}
    </div>
@endif
@if (session()->has('success') || isset($success))
    <div class="alert alert-success">
        {{ session()->get('success') ?? $success }}
    </div>
@endif
    <div class="card">
        <div class="card-header">
            <h4>
        لوحة التحكم  -  {{$title ?? ""}}
            </h4>
        </div>
        <div class="card-body">
            @forelse ($links as $link)
                <a href="{{ $link->url }}" class="btn btn-outline-primary p-3 m-2"
                    style="font-size: 16px; width: 220px;">{{ $link->name }}</a>
            @empty
            @endforelse
        </div>
    </div>
</div>

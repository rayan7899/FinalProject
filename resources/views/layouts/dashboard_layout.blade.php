<div class="container">
    <div class="card">
        <div class="card-header">
            <h4>لوحة التحكم</h4>
        </div>
        <div class="card-body">
            @forelse ($links as $link)
                <a href="{{ $link->url }}" class="btn btn-primary p-5 m-1"
                    style="font-size: 16px;">{{ $link->name }}</a>
            @empty
            @endforelse
        </div>
    </div>
</div>

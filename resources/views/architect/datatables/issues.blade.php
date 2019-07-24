
@foreach($issues as $key => $issue)
    <div class="badge badge-{{ \Illuminate\Support\Arr::random(['primary', 'secondary', 'info', 'warning', 'danger']) }}">{{ $issue->name }}</div>
@endforeach

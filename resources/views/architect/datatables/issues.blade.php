
@foreach($issues as $key => $issue)
    <div class="badge badge-{{ \Illuminate\Support\Arr::random(['primary']) }}">{{ $issue->name }}</div>
@endforeach

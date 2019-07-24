
@foreach($user->roles as $role)
    @if($role->name == "admin")
        <div class="badge badge-primary">{{ ucfirst($role->name) }}</div>
    @endif
    @if($role->name == "agent")
        <div class="badge badge-secondary">{{ ucfirst($role->name) }}</div>
    @endif
    @if($role->name == "supervisor")
        <div class="badge badge-info">{{ ucfirst($role->name) }}</div>
    @endif
@endforeach

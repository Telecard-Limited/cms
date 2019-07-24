<form action="{{ route($route . '.destroy', $model->id) }}" method="post">
    @csrf
    @method('DELETE')

    <button type="submit" class="btn btn-danger">
        <i class="pe-7s-delete-user"></i> Delete
    </button>
</form>

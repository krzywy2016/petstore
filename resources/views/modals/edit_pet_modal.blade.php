@foreach ($pets as $pet)
<div class="modal fade" id="editPetModal{{ $pet['id'] }}" tabindex="-1" role="dialog" aria-labelledby="editPetModalLabel{{ $pet['id'] }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPetModalLabel">Edycja zwierzęcia</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pets.update', ['id' => $pet['id']]) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name">Nazwa</label>
                        <input type="text" class="form-control" id="name" name="name" value="@if (isset($pet['name'])){{ $pet['name'] }}@endif">
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="available" @if ($pet['status'] === 'available') selected @endif>Available</option>
                            <option value="pending" @if ($pet['status'] === 'pending') selected @endif>Pending</option>
                            <option value="sold" @if ($pet['status'] === 'sold') selected @endif>Sold</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
            </div>
        </div>
    </div>
</div>
@endforeach

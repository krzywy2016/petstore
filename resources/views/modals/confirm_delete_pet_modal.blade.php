@foreach ($pets as $pet)
    <div class="modal fade" id="deletePetModal{{ $pet['id'] }}" tabindex="-1" role="dialog" aria-labelledby="deletePetModalLabel{{ $pet['id'] }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deletePetModalLabel{{ $pet['id'] }}">Potwierdź usunięcie zwierzęcia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć zwierzę o ID: {{ $pet['id'] }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Anuluj</button>
                    <form method="POST" action="{{ route('pets.destroy', ['id' => $pet['id']]) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Usuń</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endforeach

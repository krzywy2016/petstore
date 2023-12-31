@extends('layout')

@section('content')
    <div class="container mt-2">
        <h1>Sklep zoologiczny</h1>
        <div class="row pl-3">
            <button class="btn btn-success mb-2 mr-2" data-toggle="modal" data-target="#addPetModal">Dodaj zwierzę</button>
            <form method="POST" action="{{ route('deleteAllPets') }}"
                onsubmit="return confirm('Czy na pewno chcesz usunąć wszystkie zwierzęta?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger mb-2">Usuń wszystkie zwierzęta</button>
            </form>
            <form class="form-inline" method="GET" action="{{ route('pets.index') }}">
                <div class="form-group">
                    <label class="ml-4 mr-2" for="status">Status:</label>
                    <select class="form-control" id="status" name="status">
                        <option value="">Wszystkie</option>
                        <option value="available">Dostępne</option>
                        <option value="pending">Oczekujące</option>
                        <option value="sold">Sprzedane</option>
                    </select>
                </div>
                <div class="form-group mx-sm-3">
                    <label class="mr-2" for="id">ID:</label>
                    <input type="text" class="form-control" id="id" name="id" placeholder="Wyszukaj po ID">
                </div>
                <button type="submit" class="btn btn-primary">Filtruj</button>
                <a href="{{route('pets.index')}}"><button type="button" class="btn btn-dark ml-2">Wyczyść</button></a>
            </form>
        </div>
        <div class="row pl-3">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        <table class="table">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nazwa</th>
                    <th>Kategoria</th>
                    <th>Zdjęcie</th>
                    <th width="15%">Tagi</th>
                    <th>Status</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pets as $pet)
                    <tr>
                        <td>{{ $pet['id'] }}</td>
                        <td>
                            @if (isset($pet['name']))
                                {{ $pet['name'] }}
                            @else
                                Brak nazwy
                            @endif
                        </td>
                        <td>
                            @if (isset($pet['category']['name']))
                                {{ $pet['category']['name'] }}
                            @else
                                Brak kategorii
                            @endif
                        </td>
                        <td>
                            @if (isset($pet['photoUrls']))
                                @foreach ($pet['photoUrls'] as $photoUrl)
                                    @if (filter_var($photoUrl, FILTER_VALIDATE_URL) && preg_match('/\.(jpeg|jpg|png|gif)$/', $photoUrl))
                                        <img src="{{ $photoUrl }}" width="50px"><br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if (isset($tag['tags']))
                                @foreach ($pet['tags'] as $tag)
                                    @if (isset($tag['name']))
                                        {{ $tag['name'] }}<br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if ($pet['status'] === 'available')
                                <span class="badge badge-pill badge-success">{{ $pet['status'] }}</span>
                            @elseif($pet['status'] === 'pending')
                                <span class="badge badge-pill badge-primary">{{ $pet['status'] }}</span>
                            @elseif($pet['status'] === 'sold')
                                <span class="badge badge-pill badge-danger">{{ $pet['status'] }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#editPetModal{{ $pet['id'] }}"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="btn btn-danger" data-toggle="modal"
                                data-target="#deletePetModal{{ $pet['id'] }}"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @include('modals.new_pet_modal')
    @include('modals.confirm_delete_pet_modal')
    @include('modals.edit_pet_modal')
@endsection

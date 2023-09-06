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
                    <tr>
                        <td>{{ $pets['id'] }}</td>
                        <td>
                            @if (isset($pets['name']))
                                {{ $pets['name'] }}
                            @else
                                Brak nazwy
                            @endif
                        </td>
                        <td>
                            @if (isset($pets['category']['name']))
                                {{ $pets['category']['name'] }}
                            @else
                                Brak kategorii
                            @endif
                        </td>
                        <td>
                            @foreach ($pets['photoUrls'] as $photoUrl)
                                @if (filter_var($photoUrl, FILTER_VALIDATE_URL) && preg_match('/\.(jpeg|jpg|png|gif)$/', $photoUrl))
                                    <img src="{{ $photoUrl }}" width="50px"><br>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @if (isset($tag['tags']))
                                @foreach ($pets['tags'] as $tag)
                                    @if (isset($tag['name']))
                                        {{ $tag['name'] }}<br>
                                    @endif
                                @endforeach
                            @endif
                        </td>
                        <td>
                            @if ($pets['status'] === 'available')
                                <span class="badge badge-pill badge-success">{{ $pets['status'] }}</span>
                            @elseif($pets['status'] === 'pending')
                                <span class="badge badge-pill badge-primary">{{ $pets['status'] }}</span>
                            @elseif($pets['status'] === 'sold')
                                <span class="badge badge-pill badge-danger">{{ $pets['status'] }}</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-primary" data-toggle="modal"
                                data-target="#editPetModal{{ $pets['id'] }}"><i class="fa-regular fa-pen-to-square"></i></button>
                            <button class="btn btn-danger" data-toggle="modal"
                                data-target="#deletePetModal{{ $pets['id'] }}"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>{{--
    @include('modals.new_pet_modal')
    @include('modals.confirm_delete_pet_modal')
    @include('modals.edit_pet_modal') --}}
@endsection

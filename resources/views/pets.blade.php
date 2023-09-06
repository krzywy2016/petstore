@extends('layout')

@section('content')
    <div class="container mt-2">
        <h1>Sklep zoologiczny</h1>
        <div class="text-left">
            <a href="#" class="btn btn-success mb-2">Dodaj zwierzę</a>
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
                            @foreach ($pet['photoUrls'] as $photoUrl)
                                <img src="{{ $photoUrl }}" width="50px"><br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($pet['tags'] as $tag)
                                @if (isset($tag['name']))
                                    {{ $tag['name'] }}<br>
                                @endif
                            @endforeach
                        </td>
                        <td>
                            @if ($pet['status'] === 'available')
                                <span class="badge badge-pill badge-success">{{ $pet['status'] }}</span>
                            @endif
                        </td>
                        <td>
                            <a href="#" class="btn btn-primary"><i class="fa-regular fa-pen-to-square"></i></a>
                            <a href="#" class="btn btn-danger"><i class="fa-solid fa-trash"></i></a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

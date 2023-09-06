<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Http\Requests\PetRequest;
use Illuminate\Pagination\Paginator;

class PetController extends Controller
{
    public function index(Request $request)
    {
        $queryParameters = [];

        // Sprawdź, czy jest obecny parametr "status" w żądaniu
        if ($request->has('status')) {
            // Jeśli jest, dodaj go do tablicy parametrów zapytania
            $queryParameters['status'] = $request->input('status');
        }

        // Sprawdź, czy jest obecny parametr "id" w żądaniu
        if ($request->has('id') && !empty($request->input('id'))) {
            // Jeśli jest, dodaj go do tablicy parametrów zapytania
            $queryParameters['id'] = $request->input('id');
            $client = new Client();
            $response = $client->get('https://petstore.swagger.io/v2/pet/' . $queryParameters['id']);
        } else {
            if (empty($queryParameters)) {
                $queryParameters['status'] = 'available,sold,pending';
            }
            // Wyślij zapytanie do API z uwzględnieniem dynamicznych parametrów "status"
            $client = new Client();
            $response = $client->get('https://petstore.swagger.io/v2/pet/findByStatus', [
                'query' => $queryParameters,
            ]);
        }

        $pets = json_decode($response->getBody()->getContents(), true);

        // Paginator do spaginowania wyników na 20 na stronę do zwalidowania - nie wyświetla się paginator na froncie => do debugu
        /* $perPage = 15;
         $currentPage = Paginator::resolveCurrentPage('page');
         $currentPets = array_slice($pets, ($currentPage - 1) * $perPage, $perPage);
         $pets = new Paginator($currentPets, $perPage, $currentPage); */

        // Przetwórz odpowiedź API
        $statusCode = $response->getStatusCode(); // Pobierz kod statusu odpowiedzi HTTP

        if ($statusCode === 400) {
            // Błąd: Nieprawidłowy status
            return redirect()->back()->with('error', 'Nieprawidłowy status.');
        }

        if ($request->has('id') && !empty($request->input('id'))) {
            return view('pets_search_id', compact('pets'));
        } else {
            return view('pets', compact('pets'));
        }
    }

    public function store(PetRequest $request)
    {
        // Wyślij dane do API
        $client = new Client();
        $response = $client->post('https://petstore.swagger.io/v2/pet', [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'category' => [
                    'name' => $request->input('category.name')
                ],
                'name' => $request->input('name'),
                'photoUrls' => $request->input('photoUrls'),
                'tags' => [
                    [
                        'name' => $request->input('tags.0.name')
                    ]
                ],
                'status' => $request->input('status')
            ],
        ]);

        // Przetwórz odpowiedź API
        $statusCode = $response->getStatusCode(); // Pobierz kod statusu odpowiedzi HTTP

        // Jeśli status to 405, to oznacza błąd "Invalid input"
        if ($statusCode === 405) {
            return redirect()->back()->with('error', 'Wprowadzone dane są nieprawidłowe.');
        }


        return redirect()->back()->with('success', 'Zwierzę zostało dodane pomyślnie.');
    }

    public function destroy($id)
    {
        // Wyślij żądanie DELETE do API w celu usunięcia zwierzęcia o danym ID
        $client = new Client();
        $response = $client->delete('https://petstore.swagger.io/v2/pet/' . $id);

        // Przetwórz odpowiedź API
        $statusCode = $response->getStatusCode(); // Pobierz kod statusu odpowiedzi HTTP

        if ($statusCode === 400) {
            // Błąd: Nieprawidłowe ID
            return redirect()->back()->with('error', 'Nieprawidłowe ID zwierzęcia.');
        } elseif ($statusCode === 404) {
            // Błąd: Zwierzę nie znalezione
            return redirect()->back()->with('error', 'Zwierzę o podanym ID nie zostało znalezione.');
        }

        return redirect()->back()->with('success', 'Zwierzę zostało pomyślnie usunięte.');
    }

    public function destroyAll()
    {
        // Pobierz listę wszystkich zwierząt (lub odpowiedniego zestawu zwierząt do usunięcia)
        $client = new Client();
        $response = $client->get('https://petstore.swagger.io/v2/pet/findByStatus?status=available,sold,pending');

        $pets = json_decode($response->getBody()->getContents(), true);

        // Iteruj przez listę zwierząt i usuń każde z nich
        foreach ($pets as $pet) {
            $this->deletePet($pet['id']);
        }

        return redirect()->back()->with('success', 'Wszystkie zwierzęta zostały pomyślnie usunięte.');
    }

    // Funkcja pomocnicza do usuwania pojedynczego zwierzęcia
    private function deletePet($id)
    {
        $client = new Client();
        $client->delete('https://petstore.swagger.io/v2/pet/' . $id);

        // Przetwórz odpowiedź API
        $statusCode = $response->getStatusCode(); // Pobierz kod statusu odpowiedzi HTTP

        if ($statusCode === 400) {
            // Błąd: Nieprawidłowe ID
            return redirect()->back()->with('error', 'Nieprawidłowe ID zwierzęcia.');
        } elseif ($statusCode === 404) {
            // Błąd: Zwierzę nie znalezione
            return redirect()->back()->with('error', 'Zwierzę o podanym ID nie zostało znalezione.');
        }
    }

    public function update(Request $request, $id)
    {
        $client = new Client();
        $response = $client->post('https://petstore.swagger.io/v2/pet/' . $id, [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'json' => [
                'category' => [
                    'name' => $request->input('category.name')
                ],
                'name' => $request->input('name'),
                'photoUrls' => $request->input('photoUrls'),
                'tags' => [
                    [
                        'name' => $request->input('tags.0.name')
                    ]
                ],
                'status' => $request->input('status')
            ],
        ]);

        // Przetwórz odpowiedź API
        $statusCode = $response->getStatusCode(); // Pobierz kod statusu odpowiedzi HTTP

        // Jeśli status to 405, to oznacza błąd "Invalid input"
        if ($statusCode === 405) {
            return redirect()->back()->with('error', 'Wprowadzone dane są nieprawidłowe.');
        }

        return redirect()->back()->with('success', 'Zwierzę zostało zaktualizowane pomyślnie.');
    }
}

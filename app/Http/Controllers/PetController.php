<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;

class PetController extends Controller
{
    public function index()
    {
        $client = new Client();
        $response = $client->get('https://petstore.swagger.io/v2/pet/findByStatus?status=available,sold,pending');

        $pets = json_decode($response->getBody()->getContents(), true);

         // Paginator do spaginowania wyników na 20 na stronę do zwalidowania - nie wyświetla się paginator na froncie
         /* $perPage = 15;
         $currentPage = Paginator::resolveCurrentPage('page');
         $currentPets = array_slice($pets, ($currentPage - 1) * $perPage, $perPage);
         $pets = new Paginator($currentPets, $perPage, $currentPage); */

        return view('pets', compact('pets'));
    }

    public function store(Request $request)
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
        $responseData = json_decode($response->getBody()->getContents(), true); // Pobierz treść odpowiedzi w formie JSON

        // Teraz możesz wykorzystać $statusCode i $responseData w zależności od potrzeb

        // Przykład: Wyświetlenie odpowiedzi w celach debugowania
        //dd($statusCode, $responseData);

        return redirect()->back()->with('success', 'Zwierzę zostało dodane pomyślnie.');
    }

    public function destroy($id)
    {
        // Wyślij żądanie DELETE do API w celu usunięcia zwierzęcia o danym ID
        $client = new Client();
        $response = $client->delete('https://petstore.swagger.io/v2/pet/' . $id);

        // Przetwórz odpowiedź API, jeśli to konieczne

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
        $responseData = json_decode($response->getBody()->getContents(), true); // Pobierz treść odpowiedzi w formie JSON

        // Teraz możesz wykorzystać $statusCode i $responseData w zależności od potrzeb

        // Przykład: Wyświetlenie odpowiedzi w celach debugowania
        dd($statusCode, $responseData);

        return redirect()->back()->with('success', 'Zwierzę zostało zaktualizowane pomyślnie.');
    }

}

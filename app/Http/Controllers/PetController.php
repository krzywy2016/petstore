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
        $response = $client->get('https://petstore.swagger.io/v2/pet/findByStatus?status=available');

        $pets = json_decode($response->getBody()->getContents(), true);

         // Paginator do spaginowania wyników na 20 na stronę do zwalidowania - nie wyświetla się paginator na froncie
         /* $perPage = 15;
         $currentPage = Paginator::resolveCurrentPage('page');
         $currentPets = array_slice($pets, ($currentPage - 1) * $perPage, $perPage);
         $pets = new Paginator($currentPets, $perPage, $currentPage); */

        return view('pets', compact('pets'));
    }
}

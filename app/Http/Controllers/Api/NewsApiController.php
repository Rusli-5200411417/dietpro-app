<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NewsApiController extends Controller
{
    public function getNews()
    {
        $apiKey = 'e7e2be8ec0b74f53b4e446f0227cef86';
        $country = 'id';
        $category = 'health';

        $response = Http::get("https://newsapi.org/v2/top-headlines", [
            'country' => $country,
            'category' => $category,
            'apiKey' => $apiKey,
        ]);

        if ($response->successful()) {
            // If the API request is successful, return the JSON response
            return $response->json();
        } else {
            // Handle the case where the API request fails
            return response()->json(['message' => 'Failed to fetch news'], 500);
        }
    }
}

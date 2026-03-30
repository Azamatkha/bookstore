<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class TestController extends Controller
{
    public function test()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('SUPABASE_KEY'),
        ])->get(env('SUPABASE_URL') . '/storage/v1/bucket');

        dd($response->body());
    }
}

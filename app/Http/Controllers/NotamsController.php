<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class NotamsController extends Controller
{
    private $notamJsonUri = "resources/data/nattrak/notams.json";

    public function index()
    {
        return view('administration.notams', [
            'jsonContent' => Storage::get($this->notamJsonUri)
        ]);
    }
}

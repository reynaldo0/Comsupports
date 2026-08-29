<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat.index');
    }

    public function send(Request $request)
    {
        $request->validate(['message' => 'required|string|max:1000']);
        // Akan diisi di Pertemuan 5 (integrasi Gemini)
        return response()->json(['reply' => 'Bot sedang disiapkan...']);
    }
}

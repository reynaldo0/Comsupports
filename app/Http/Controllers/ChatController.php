<?php

namespace App\Http\Controllers;

use App\Models\ChatHistory;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->session()->has('chat_session_id')) {
            $request->session()->put('chat_session_id', Str::uuid());
        }

        $sessionId = $request->session()->get('chat_session_id');
        $histories  = ChatHistory::where('session_id', $sessionId)
            ->orderBy('created_at')
            ->get();

        return view('chat.index', compact('histories'));
    }

    public function send(Request $request, GeminiService $gemini)
    {
        $request->validate(['message' => 'required|string|max:1000']);

        if (!$request->session()->has('chat_session_id')) {
            $request->session()->put('chat_session_id', Str::uuid());
        }

        $sessionId   = $request->session()->get('chat_session_id');
        $userMessage = $request->message;

        ChatHistory::create(['session_id' => $sessionId, 'sender' => 'user', 'message' => $userMessage]);

        $reply = $gemini->chat($userMessage);

        ChatHistory::create(['session_id' => $sessionId, 'sender' => 'bot', 'message' => $reply]);

        return response()->json(['reply' => $reply]);
    }

    public function resetSession(Request $request)
    {
    $request->session()->forget('chat_session_id');
    return redirect()->route('chat.index');
    }
}

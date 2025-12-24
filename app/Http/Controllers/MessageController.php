<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function send(Request $req)
    {
        $req->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $msg = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $req->receiver_id,
            'message' => $req->message,
        ]);

        return response()->json($msg, 201);
    }

    public function chatWith($userId)
    {
        $authId = auth()->id();

        $messages = Message::with(['sender','receiver'])
            ->where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })->orderBy('created_at', 'asc')
            ->paginate(30);

        Message::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

    }
}

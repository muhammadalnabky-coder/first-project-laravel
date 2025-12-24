<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function list()
    {
        return response()->json(
            auth()->user()->notifications()->orderByDesc('created_at')->get()
        );
    }

    public function markRead($id)
    {
        $notif = Notification::where('user_id', auth()->id())->find($id);
        if (!$notif) {
            return response()->json(['error' => 'Notification not found'], 404);
        }


        $notif->update(['is_read' => true]);

        return response()->json(['message' => 'Marked as read']);
    }

    public function create(Request $req)
    {
        $req->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'nullable|string',
            'message' => 'required|string'
        ]);

        $notif = Notification::create([
            'user_id' => $req->user_id,
            'title' => $req->title,
            'message' => $req->message,
        ]);

        return response()->json($notif, 201);
    }
}

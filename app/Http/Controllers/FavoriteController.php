<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $data= auth()->user()->favorites()->with('apartment')->get();
        if($data->isEmpty()){
            return response()->json(['message' => 'No favorites yet']);
        }
        return response()->json($data);
    }

    public function toggle(Request $req)
    {
        $req->validate([
            'apartment_id' => 'required|exists:apartments,id'
        ]);

        $fav = Favorite::where('user_id', auth()->id())
            ->where('apartment_id', $req->apartment_id)
            ->first();

        if ($fav) {
            $fav->delete();
            return response()->json(['status' => 'removed']);
        }

        Favorite::create([
            'user_id' => auth()->id(),
            'apartment_id' => $req->apartment_id
        ]);

        return response()->json(['status' => 'added']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\searchApartmentRequest;
use App\Http\Requests\storeApartmentRequest;
use App\Http\Requests\updateApartmentRequest;
use Illuminate\Http\Request;
use App\Models\Apartment;
use App\Models\ApartmentImage;
use Tymon\JWTAuth\Facades\JWTAuth;

class ApartmentController extends Controller
{
    public function index()
    {
        $apartment = Apartment::with(['images',
                'ratings',
        ])->get();

        if (!$apartment) {
            return response()->json([
                'status' => false,
                'message' => 'Not Found Any Apartment '
            ], 404);
        }

        return response()->json([
            'status' => true,
            'count' => $apartment->count(),
            'apartments' => $apartment
        ], 200);
    }

    public function show($id)
    {
        $apartment = Apartment::with('images')->find($id);

        if (!$apartment) {
            return response()->json([
                'status' => false,
                'message' => 'Apartment not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'apartment' => $apartment
        ], 200);
    }

    public function store(storeApartmentRequest $req)
    {
        $data = $req->validated();
        $data['owner_id']= auth()->id();
        $apartment = Apartment::create($data);

        if ($req->hasFile('images')) {
            foreach ($req->file('images') as $img) {
                $name = time().'_'.uniqid().'.'.$img->getClientOriginalExtension();
                $img->move(public_path('uploads/apartments'), $name);

                ApartmentImage::create([
                    'apartment_id' => $apartment->id,
                    'image' => $name
                ]);
            }
        }
        return response()->json([
            'message' => 'Apartment created successfully',
            'apartment' => $apartment->load('images')
        ]);
    }

    public  function update(updateApartmentRequest $request, $id)
    {
        $user = JWTAuth::parseToken()->authenticate();
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        if ($user->id!= $apartment->owner_id) {
            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
        }

        $data = $request->validated();
        $data['owner_id']= auth()->id();

        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }
        $apartment->update($data);
        return response()->json(['message' => 'Apartment updated successfully',
            'apartment' => $apartment->load('images')], 200);
    }

    public function search(searchApartmentRequest $req)
    {
        $apartments = Apartment::with('images')

            ->when($req->city, function ($q) use ($req) {
                $q->where('city', 'LIKE', "%{$req->city}%");
            })

            ->when($req->rooms, function ($q) use ($req) {
                $q->where('rooms', $req->rooms);
            })

            ->when($req->area, function ($q) use ($req) {
                $q->where('area', 'LIKE', "%{$req->area}%");
            })

            ->when($req->address, function ($q) use ($req) {
                $q->where('address', 'LIKE', "%{$req->address}%");
            })

            ->when($req->min, function ($q) use ($req) {
                $q->where('price_per_day', '>=', $req->min);
            })

            ->when($req->max, function ($q) use ($req) {
                $q->where('price_per_day', '<=', $req->max);
            })

            ->get();

        if ($apartments->isEmpty()) {
            return response()->json(['message' => 'No apartments found'], 404);
        }

        return response()->json([
            'count' => $apartments->count(),
            'apartments' => $apartments], 200);
    }

    public function destroy($id)
    {
        $user = JWTAuth::parseToken()->authenticate();

        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(["status"=> false,
                'message' => 'Apartment not found'], 404);
        }

        if ($user->id!= $apartment->owner_id) {
            return response()->json(["status"=> true,
                'message' => 'Invalid, you are not the owner'], 400);
        }

        $apartment->delete();
        return response()->json(['message' => 'Apartment deleted successfully'], 200);
    }
}

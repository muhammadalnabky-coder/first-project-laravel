<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\ApartmentImage;
use Illuminate\Http\Request;

class AprtmentImagesController extends Controller
{
    public function index($id){

        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        if (auth()->id()!= $apartment->owner_id) {
            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
        }
        $imges=null;

        foreach ($apartment->images as $img) {
            $imges[]=$img;
        }

        return response()->json(['images'=>$imges]);
    }

    public function store(Request $request, $id)
    {
        $apartment = Apartment::find($id);

        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        if (auth()->id()!= $apartment->owner_id) {
            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
        }

        $request->validate([
            'image_url'   => 'nullable',
            'image_url.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if (!$request->hasFile('image_url')) {
            return response()->json(['message' => 'No images uploaded'], 400);
        }

        $files = $request->file('image_url');

        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $img) {

            $imageName = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads/apartments'), $imageName);

            $image=ApartmentImage::create([
                'apartment_id' => $apartment->id,
                'image_url'    => asset('uploads/apartments/' .$imageName)
            ]);
        }

        return response()->json(['status'=>true,
            'image'=>$image,
            'message' => 'Images stored successfully'], 201);
    }

    public function update(Request $request, $id){
        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        if (auth()->id()!= $apartment->owner_id) {
            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
        }
//===============================================================
         //$this->destroy($id);
        $request->validate([
            'image_url'   => 'nullable',
            'image_url.*' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);


        if (!$request->hasFile('image_url')) {
            return response()->json(['message' => 'No images uploaded'], 400);
        }

        $files = $request->file('image_url');
        if (!is_array($files)) {
            $files = [$files];
        }

        foreach ($files as $img) {
            $imageName = time() . '_' . uniqid() . '.' . $img->getClientOriginalExtension();
            $img->move(public_path('uploads/apartments'), $imageName);
        }

        $image=ApartmentImage::create([
            'apartment_id' => $apartment->id,
            'image_url'    => asset('uploads/apartments/' . $imageName),
        ]);


        return response()->json(['status'=>true,
            'image'=>$image,
            'message' => 'Images updated successfully'], 200);
    }

    public function destroy($id){

        $apartment = Apartment::find($id);
        if (!$apartment) {
            return response()->json(['message' => 'Apartment not found'], 404);
        }

        if (auth()->id()!= $apartment->owner_id) {
            return response()->json(['message' => 'Invalid, you are not the owner'], 400);
        }

        foreach ($apartment->images as $image) {
            $apartmentImage = ApartmentImage::find($image->id);
            $apartmentImage->delete();
        }
        return response()->json(['message' => 'Image deleted'], 201);
    }
}

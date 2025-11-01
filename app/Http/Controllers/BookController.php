<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $book= Book::all();
        return response()->json(['data' => $book
                                    ,'message'=>'these are all books store'], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book =$request->validated();
        $book=Book::query()->create($book);
        return response()->json(['data' => $book
            ,'message'=>'successfully store the book'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book=Book::query()->find($id);
        if(is_null($book)){
            return response()->json(['message'=>'this book is not found'], 404);
        }
        else{
        return response()->json(['data' => $book
            ,'message'=>'this is book store'], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBookRequest $request, string $id)
    {
        $book =$request->validated();
        $book=Book::query()->find($id);
        if(is_null($book)){
            return response()->json(['message'=>'this book is not found'], 404);
        }
        else {
            $book = Book::query()->create($book);
            return response()->json(['data' => $book
                , 'message' => 'successfully update the book'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book=Book::query()->find($id);
        if(is_null($book)){
            return response()->json(['message'=>'this book is not found'], 404);
        }
        else{
        $book->delete();
        return response()->json(['message'=>'book deleted successfully'], 200);
        }
    }

}

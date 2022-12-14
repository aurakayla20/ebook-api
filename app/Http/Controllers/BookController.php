<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::get();

        if (count($books) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'data successfully accepted',
                'data' => $books
            ], 202);
        }

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully accepted',
            'data' => 'no data available'
        ], 202);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = validator($request->all(), [
            'title' => ['required','string','max:255','unique:books,title'],
            'description' => ['required','string'],
            'author' => ['required','string','max:255'],
            'publisher' => ['required','string','max:255'],
            'date_of_issue' => ['required','date','date_format:Y-m-d']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $book = Book::create($validator->validated());

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully created',
            'data' => $book
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'user not found in our database'
            ], 404);
        }

        return response()->json([
            'code' => 206,
            'status' => 'success',
            'message' => 'data successfully accepted',
            'data' => $book
        ], 206);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = validator($request->all(), [
            'title' => ['nullable','string','max:255','unique:books,title,'.$id],
            'description' => ['nullable','string'],
            'author' => ['nullable','string','max:255'],
            'publisher' => ['nullable','string','max:255'],
            'date_of_issue' => ['nullable','date','date_format:Y-m-d']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'status' => 'error',
                'message' => 'data not match with our validation',
                'data' => $validator->errors()
            ], 422);
        }

        $book = Book::find($id);
        
        if (empty($book)) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'book not found in our database'
            ], 404);
        }

        $book->update($validator->validated());

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully updated',
            'data' => $book
        ], 202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (empty($book)) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'book not found in our database'
            ], 404);
        }

        $book->delete();

        $books = Book::get();

        if (count($books) > 0) {
            return response()->json([
                'code' => 202,
                'status' => 'success',
                'message' => 'data successfully removed',
                'data' => $books
            ], 202);
        }

        return response()->json([
            'code' => 202,
            'status' => 'success',
            'message' => 'data successfully removed',
            'data' => 'no data available'
        ], 202);
    }
}

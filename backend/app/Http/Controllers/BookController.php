<?php

namespace App\Http\Controllers;

use App\Http\Repositories\BookRepository;
use App\Http\Requests\ListBookRequest;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BookController extends Controller
{
    private $module = 'Book';

    private $repo;

    public function __construct()
    {
        $this->repo = new BookRepository();
    }

    public function get(Book $book)
    {
        return response()->json([
            'book' => $book
        ]);
    }

    public function getList(ListBookRequest $request)
    {
        try {
            return response()->json([
                'data' => $this->repo->getDTList($request->validated()),
            ], 200);
        } catch (\Exception $e) {
            \Log::error('[Book][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Book][store]: ' . $e->getMessage());
            \Log::error('[Book][store]: ' . $e->getLine());

            return response()->json([
                'data' => [],
            ], 200);
        }
    }

    public function store(StoreBookRequest $request)
    {
        $book = new Book();

        Gate::authorize('store', $book);

        try {
            DB::beginTransaction();
            $book = $this->repo->createRecord($request->validated(), $book);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully created.',
                'data' => $book,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Book][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Book][store]: ' . $e->getMessage());
            \Log::error('[Book][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        Gate::authorize('update', $book);

        try {
            DB::beginTransaction();
            $book = $this->repo->updateRecord($request->validated(), $book);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully updated',
                'data' => $book,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Book][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Book][update]: ' . $e->getMessage());
            \Log::error('[Book][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Book $book)
    {
        Gate::authorize('destroy', $book);

        try {
            DB::beginTransaction();
            $book->delete();
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Book][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Book][destroy]: ' . $e->getMessage());
            \Log::error('[Book][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function getDropdownList()
    {
        $book = new Book();

        $bookDropdownList = $book->select([
            DB::raw('books.id AS name'),
            DB::raw('books.name AS label'),
            'books.image_name',
            'books.image_extension',
        ])->get();

        return response()->json([
            'dropdown_list' => $bookDropdownList
        ]);
    }
}

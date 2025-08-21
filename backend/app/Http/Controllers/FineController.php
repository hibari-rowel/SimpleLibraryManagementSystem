<?php

namespace App\Http\Controllers;

use App\Http\Repositories\FineRepository;
use App\Http\Requests\StoreFineRequest;
use App\Http\Requests\UpdateFineRequest;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FineController extends Controller
{
    private $module = 'Fine';

    private $repo;

    public function __construct()
    {
        $this->repo = new FineRepository();
    }

    public function get(Fine $fine)
    {
        //
    }

    public function getAll(Request $request)
    {
        //
    }

    public function store(StoreFineRequest $request)
    {
        $fine = new Fine();
        Gate::authorize('store', $fine);

        try {
            DB::beginTransaction();
            $fine = $this->repo->createRecord($request->validated(), $fine);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully created.',
                'data' => $fine,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Fine][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Fine][store]: ' . $e->getMessage());
            \Log::error('[Fine][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateFineRequest $request, Fine $fine)
    {
        Gate::authorize('update', $fine);

        try {
            DB::beginTransaction();
            $fine = $this->repo->updateRecord($request->validated(), $fine);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully updated',
                'data' => $fine,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Fine][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Fine][update]: ' . $e->getMessage());
            \Log::error('[Fine][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Fine $fine)
    {
        Gate::authorize('destroy', $fine);

        try {
            DB::beginTransaction();
            $fine->delete();
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Fine][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Fine][destroy]: ' . $e->getMessage());
            \Log::error('[Fine][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }
}

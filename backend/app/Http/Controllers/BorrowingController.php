<?php

namespace App\Http\Controllers;

use App\Http\Repositories\BorrowingRepository;
use App\Http\Requests\StoreBorrowingRequest;
use App\Http\Requests\UpdateBorrowingRequest;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class BorrowingController extends Controller
{
    private $module = 'Borrowing';

    private $repo;

    public function __construct()
    {
        $this->repo = new BorrowingRepository();
    }

    public function get(Borrowing $borrowing)
    {
        return response()->json([
            'borrowing' => $borrowing
        ]);
    }

    public function getDTList(Request $request)
    {
        $params = $request->only(['draw', 'start', 'length', 'order', 'search', 'user_id', 'status']);

        list($data, $total) = $this->repo->getDTList($params);

        return response()->json([
            'draw' => $params['draw'],
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ]);
    }

    public function store(StoreBorrowingRequest $request)
    {
        $borrowing = new Borrowing();
        Gate::authorize('store', $borrowing);

        try {
            DB::beginTransaction();
            $borrowing = $this->repo->createRecord(
                $request->validated(),
                $borrowing
            );
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully created.',
                'data' => $borrowing,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Borrowing][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Borrowing][store]: ' . $e->getMessage());
            \Log::error('[Borrowing][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateBorrowingRequest $request, Borrowing $borrowing)
    {
        Gate::authorize('update', $borrowing);

        try {
            DB::beginTransaction();
            $borrowing = $this->repo->updateRecord($request->validated(), $borrowing);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully updated',
                'data' => $borrowing,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Borrowing][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Borrowing][update]: ' . $e->getMessage());
            \Log::error('[Borrowing][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Borrowing $borrowing)
    {
        Gate::authorize('destroy', $borrowing);

        try {
            DB::beginTransaction();
            $borrowing->delete();
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Borrowing][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Borrowing][destroy]: ' . $e->getMessage());
            \Log::error('[Borrowing][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }
}

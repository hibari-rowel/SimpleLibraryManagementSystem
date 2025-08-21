<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ReservationRepository;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class ReservationController extends Controller
{
    private $module = 'Reservation';

    private $repo;

    public function __construct()
    {
        $this->repo = new ReservationRepository();
    }

    public function get(Reservation $reservation)
    {
        return response()->json([
            'reservation' => $reservation
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

    public function store(StoreReservationRequest $request)
    {
        $reservation = new Reservation();
        Gate::authorize('store', $reservation);

        try {
            DB::beginTransaction();
            $reservation = $this->repo->createRecord($request->validated(), $reservation);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully created.',
                'data' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Reservation][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Reservation][store]: ' . $e->getMessage());
            \Log::error('[Reservation][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        Gate::authorize('update', $reservation);

        try {
            DB::beginTransaction();
            $reservation = $this->repo->updateRecord($request->validated(), $reservation);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully updated',
                'data' => $reservation,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Reservation][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Reservation][update]: ' . $e->getMessage());
            \Log::error('[Reservation][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Reservation $reservation)
    {
        Gate::authorize('destroy', $reservation);

        try {
            DB::beginTransaction();
            $reservation->delete();
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Reservation][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Reservation][destroy]: ' . $e->getMessage());
            \Log::error('[Reservation][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }
}

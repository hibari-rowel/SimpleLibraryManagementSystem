<?php

namespace App\Http\Controllers;

use App\Http\Repositories\UserRepository;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    private $module = 'User';

    private $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function get(User $user)
    {
        return response()->json([
            'user' => $user
        ]);
    }

    public function getDTList(Request $request)
    {
        $params = $request->only(['draw', 'start', 'length', 'order', 'search']);

        list($data, $total) = $this->repo->getDTList($params);

        return response()->json([
            'draw' => $params['draw'],
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
        ], 200);
    }

    public function store(StoreUserRequest $request)
    {
        $user = new User();
        Gate::authorize('store', $user);

        try {
            DB::beginTransaction();
            $user = $this->repo->createRecord($request->validated(), $user);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully created.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[User][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[User][store]: ' . $e->getMessage());
            \Log::error('[User][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        try {
            DB::beginTransaction();
            $user = $this->repo->updateRecord($request->validated(), $user);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully updated',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[User][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[User][update]: ' . $e->getMessage());
            \Log::error('[User][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        Gate::authorize('destroy', $user);

        try {
            DB::beginTransaction();
            $user->delete();
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[User][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[User][destroy]: ' . $e->getMessage());
            \Log::error('[User][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function getDropdownList()
    {
        $user = new User();

        $userDropdownList = $user->select([
            DB::raw('users.id AS name'),
            DB::raw('CONCAT_WS(" ", users.first_name, users.middle_name, users.last_name) AS label')
        ])->where('users.id', '!=', $this->repo->user->id)->get();

        return response()->json([
            'dropdown_list' => $userDropdownList
        ]);
    }
}

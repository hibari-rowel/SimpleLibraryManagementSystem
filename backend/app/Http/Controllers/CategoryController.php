<?php

namespace App\Http\Controllers;

use App\Http\Repositories\CategoryRepository;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    private $module = 'Category';

    private $repo;

    public function __construct()
    {
        $this->repo = new CategoryRepository();
    }

    public function get(Category $category)
    {
        return response()->json([
            'category' => $category
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
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = new Category();
        Gate::authorize('store', $category);

        try {
            DB::beginTransaction();
            $category = $this->repo->createRecord($request->validated(), $category);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully created.',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Category][store]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Category][store]: ' . $e->getMessage());
            \Log::error('[Category][store]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        Gate::authorize('update', $category);

        try {
            DB::beginTransaction();
            $category = $this->repo->updateRecord($request->validated(), $category);
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully updated',
                'data' => $category,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Category][update]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Category][update]: ' . $e->getMessage());
            \Log::error('[Category][update]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        Gate::authorize('destroy', $category);

        try {
            DB::beginTransaction();
            $category->delete();
            DB::commit();

            return response()->json([
                'message' => $this->module . ' ' . 'record successfully deleted'
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('[Category][destroy]: An error occurred while processing the request' . $e->getMessage() . ' ' . $e->getLine());
            \Log::error('[Category][destroy]: ' . $e->getMessage());
            \Log::error('[Category][destroy]: ' . $e->getLine());

            return response()->json([
                'message' => 'Something went wrong. Please contact support for assistance.'
            ], 500);
        }
    }

    public function getDropdownList()
    {
        $category = new Category();

        $categoryDropdownList = $category->select([
            DB::raw('categories.id AS name'),
            DB::raw('categories.name AS label'),
        ])->get();

        return response()->json([
            'dropdown_list' => $categoryDropdownList
        ]);
    }
}

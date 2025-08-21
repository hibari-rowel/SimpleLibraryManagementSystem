<?php

namespace App\Http\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CategoryRepository extends BaseRepository implements RepositoryInterface
{
    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {
        $data['updated_by'] = $this->user->id;

        return $this->save($data, $model);
    }

    public function getDTList($params)
    {
        $params = $this->getDTParams($params);
        $select = Arr::pluck($this->categoryDTColumns(), 'field');
        $alias = Arr::pluck($this->categoryDTColumns(), 'alias');

        $qb = DB::table('categories')
            ->select($select)
            ->whereNull('deleted_at');

        if (!empty($params['search_key'])) {
            $qb->where('categories.name', 'LIKE', $params['search_key'] . '%');
        }

        if (!empty($params['order'])) {
            foreach ($params['order'] as $order) {
                $qb->orderBy($alias[$order['column']], $order['dir']);
            }
        }

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function categoryDTColumns()
    {
        return [
            [
                'field' => 'categories.id',
                'alias' => 'id',
            ],
            [
                'field' => 'categories.name',
                'alias' => 'name',
            ],
            [
                'field' => 'categories.description',
                'alias' => 'description',
            ],
            [
                'field' => 'categories.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}

<?php

namespace App\Http\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository implements RepositoryInterface
{
    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user ? $this->user->id : null;
        $data['updated_by'] = $this->user ? $this->user->id : null;

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {
        $data['updated_by'] = $this->user ? $this->user->id : null;

        return $this->save($data, $model);
    }

    public function getDTList($params)
    {
        $params = $this->getDTParams($params);
        $select = Arr::pluck($this->userDTColumns(), 'field');
        $alias = Arr::pluck($this->userDTColumns(), 'alias');

        $qb = DB::table('users')
            ->select($select)
            ->where('id', '!=', $this->user->id)
            ->whereNull('deleted_at');

        if (!empty($params['search_key'])) {
            $qb->where(function ($query) use ($params){
                $query->where('users.first_name', 'LIKE', $params['search_key'] . '%');
                $query->orWhere('users.last_name', 'LIKE', $params['search_key'] . '%');
            });
        }

        if (!empty($params['order'])) {
            foreach ($params['order'] as $order) {
                $qb->orderBy($alias[$order['column']], $order['dir']);
            }
        }

        $result = $qb->paginate($params['limit'], ['*'], 'page', $params['page_num']);

        return [$result->items(), $result->total()];
    }

    private function userDTColumns()
    {
        return [
            [
                'field' => 'users.id',
                'alias' => 'id',
            ],
            [
                'field' => DB::raw('CONCAT_WS(" ", users.first_name, users.middle_name, users.last_name) AS name'),
                'alias' => 'name',
            ],
            [
                'field' => 'users.membership_type',
                'alias' => 'membership_type',
            ],
            [
                'field' => 'users.status',
                'alias' => 'status',
            ],
            [
                'field' => 'users.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}

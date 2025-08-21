<?php

namespace App\Http\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ReservationRepository extends BaseRepository implements RepositoryInterface
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
        $alias = Arr::pluck($this->categoryDTColumns(), 'alias');
        $select = array_merge(
            ['reservations.user_id', 'reservations.book_id'],
            Arr::pluck($this->categoryDTColumns(), 'field')
        );

        $qb = DB::table('reservations')
            ->select($select)
            ->whereNull('reservations.deleted_at')
            ->whereNull('users.deleted_at')
            ->whereNull('books.deleted_at')
            ->leftJoin('users', 'reservations.user_id', '=', 'users.id')
            ->leftJoin('books', 'reservations.book_id', '=', 'books.id');

        if (!empty($params['user_id'])) {
            $qb->where('reservations.user_id', $params['user_id']);
        }

        if (!empty($params['status'])) {
            $qb->whereIn('reservations.status', $params['status']);
        }

        if (!empty($params['search_key'])) {
            $qb->where(function ($query) use ($params){
                $query->where('users.first_name', 'LIKE', $params['search_key'] . '%');
                $query->orWhere('users.last_name', 'LIKE', $params['search_key'] . '%');
                $query->orWhere('books.name', 'LIKE', $params['search_key'] . '%');
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

    private function categoryDTColumns()
    {
        return [
            [
                'field' => 'reservations.id',
                'alias' => 'id',
            ],
            [
                'field' => DB::raw('CONCAT_WS(" ", users.first_name, users.middle_name, users.last_name) AS user_name'),
                'alias' => 'user_name',
            ],
            [
                'field' => 'books.name as book_name',
                'alias' => 'book_name',
            ],
            [
                'field' => 'reservations.status',
                'alias' => 'status',
            ],
            [
                'field' => 'reservations.reservation_date',
                'alias' => 'borrow_date',
            ],
            [
                'field' => 'reservations.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}

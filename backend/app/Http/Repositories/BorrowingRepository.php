<?php

namespace App\Http\Repositories;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BorrowingRepository extends BaseRepository implements RepositoryInterface
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

        $select = array_merge(
            ['borrowings.user_id', 'borrowings.book_id'],
            Arr::pluck($this->categoryDTColumns(), 'field')
        );

        $alias = Arr::pluck($this->categoryDTColumns(), 'alias');

        $qb = DB::table('borrowings')
            ->select($select)
            ->whereNull('borrowings.deleted_at')
            ->whereNull('users.deleted_at')
            ->whereNull('books.deleted_at')
            ->leftJoin('users', 'borrowings.user_id', '=', 'users.id')
            ->leftJoin('books', 'borrowings.book_id', '=', 'books.id');

        if (!empty($params['user_id'])) {
            $qb->where('borrowings.user_id', $params['user_id']);
        }

        if (!empty($params['status'])) {
            $qb->whereIn('borrowings.status', $params['status']);
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
                'field' => 'borrowings.id',
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
                'field' => 'borrowings.status',
                'alias' => 'status',
            ],
            [
                'field' => 'borrowings.borrow_date',
                'alias' => 'borrow_date',
            ],
            [
                'field' => 'borrowings.due_date',
                'alias' => 'due_date',
            ],
            [
                'field' => 'borrowings.return_date',
                'alias' => 'return_date',
            ],
            [
                'field' => 'borrowings.created_at',
                'alias' => 'created_at',
            ],
        ];
    }
}

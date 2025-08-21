<?php

namespace App\Http\Repositories;

class FineRepository extends BaseRepository implements RepositoryInterface
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
        // TODO: Implement getUserDTList() method.
    }
}

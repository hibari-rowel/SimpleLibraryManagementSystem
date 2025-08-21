<?php

namespace App\Http\Repositories;

interface RepositoryInterface
{
    public function createRecord($data, $model);

    public function updateRecord($data, $model);

    public function getDTList($params);
}

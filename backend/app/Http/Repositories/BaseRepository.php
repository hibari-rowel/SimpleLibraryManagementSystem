<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BaseRepository
{
    public array $dirtyValues = [];

    public array $dirtyKeyValues = [];

    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }

    public function save($data, $model)
    {
        $this->dirtyValues = [];

        $model->fill($data);

        $this->dirtyValues = $model->getDirty();
        $this->dirtyKeyValues = array_keys($this->dirtyValues);

        $model = $this->afterModelFill($data, $model);
        if ($model->save()) {
            $this->afterSave($data, $model);
            return $model;
        }

        return false;
    }

    public function afterModelFill($data, $model)
    {
        return $model;
    }

    public function afterSave($data, $model)
    {

    }

    public function getDTParams($params)
    {
        $params['limit'] = $params['length'];

        $params['search_key'] = null;
        if (!empty($params['search']['value'])) {
            $params['search_key'] = trim($params['search']['value']);
        }

        $params['page_num'] = 1;
        if ($params['start'] > 0 && $params['length'] > 0) {
            $params['page_num'] = abs(floor($params['start'] / $params['length']) + 0);
        }

        return $params;
    }
}

<?php

namespace App\Http\Repositories;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookRepository extends BaseRepository implements RepositoryInterface
{
    CONST BOOK_COVER_IMAGE_BASE_PATH = "uploads/books/cover_image/";

    public function createRecord($data, $model)
    {
        $data['created_by'] = $this->user->id;
        $data['updated_by'] = $this->user->id;

        $this->getImageParams($data);

        return $this->save($data, $model);
    }

    public function updateRecord($data, $model)
    {
        $data['updated_by'] = $this->user->id;

        $this->getImageParams($data);

        return $this->save($data, $model);
    }

    public function afterSave($data, $model)
    {
        $image = !empty($data['image']) ? $data['image'] : null;
        if (!empty($image) && $image instanceof UploadedFile && $image->isValid()) {
            $filename = $data['image_name'] . '.' . $data['image_extension'];
            Storage::disk('public')->putFileAs(self::BOOK_COVER_IMAGE_BASE_PATH, $image, $filename);
        }

        if (!empty($this->dirtyValues['image_name']) && !empty($this->dirtyValues['image_extension'])) {
            $oldFilename = $this->dirtyValues['image_name'] . '.' . $this->dirtyValues['image_extension'];
            if (!$model->wasRecentlyCreated
                && in_array('image_name', $this->dirtyKeyValues)
                && in_array('image_extension', $this->dirtyKeyValues)
                && Storage::disk('public')->exists(self::BOOK_COVER_IMAGE_BASE_PATH . $oldFilename)
            ) {
                Storage::disk('public')->delete(self::BOOK_COVER_IMAGE_BASE_PATH . $oldFilename);
            }
        }
    }

    public function getDTList($params)
    {
        $select = [
            'books.id',
            'books.name',
            'books.author',
            'categories.name as category_name',
            'books.total_copies',
            'books.image_name',
            'books.image_extension',
            'books.created_at',
            DB::raw("CONCAT('" . asset('') . "storage/uploads/books/cover_image/', books.image_name, '.', books.image_extension) as image_url"),
        ];

        $qb = DB::table('books')
            ->select($select)
            ->leftJoin('categories', 'books.category_id', '=', 'categories.id')
            ->whereNull('books.deleted_at')
            ->whereNull('categories.deleted_at');

        if (!empty($params['name'])) {
            $qb->where('books.name', 'LIKE', $params['name'] . '%');
        }

        if (!empty($params['category_id'])) {
            $qb->where('categories.id', $params['category_id']);
        }

        $result = $qb->orderBy('books.' . $params['order_by'], $params['order'])->limit(9);

        return $result->get();
    }

    private function getImageParams(&$data)
    {
        $image = !empty($data['image']) ? $data['image'] : null;
        if (!empty($image) && $image instanceof UploadedFile && $image->isValid()) {
            $newFilename = Str::uuid();
            $data['image_name'] = $newFilename;
            $data['original_image_name'] = $image->getClientOriginalName();
            $data['image_extension'] = $image->getClientOriginalExtension();
            $data['image_mime_type'] = $image->getClientMimeType();
        }
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Book extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'books';

    protected $fillable = [
        'name',
        'description',
        'author',
        'category_id',
        'publisher',
        'publication_year',
        'total_copies',
        'shelf_location',
        'image_name',
        'original_image_name',
        'image_extension',
        'image_mime_type',
        'created_by',
        'updated_by',
    ];

    protected $appends = [
        'category_name',
        'image_url',
    ];

    // ####################### Custom Functions ########################
    public static function getFieldValidations($params): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'author' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'publisher' => ['nullable', 'string'],
            'publication_year' => ['nullable', 'date'],
            'total_copies' => ['nullable', 'integer'],
            'shelf_location' => ['nullable', 'string'],
            'image' => ['nullable', 'file', 'image', 'mimes:jpeg,png,jpg,gif', 'max:10048'],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }

    // ######################### Relationships #########################

    // ##################### Accessors & Mutators ######################
    protected function categoryName(): Attribute
    {
        return new Attribute(
            get: function() {
                    $category = DB::table('categories')
                        ->select(['id', 'name'])
                        ->where('id', $this->getOriginal('category_id'))
                        ->whereNull('deleted_at')
                        ->first();

                    return !empty($category) ? $category->name : null;
                },
        );
    }

    protected function imageUrl(): Attribute
    {
        return new Attribute(
            get: function() {
                    return asset("storage/uploads/books/cover_image/" . $this->getOriginal('image_name') . '.' . $this->getOriginal('image_extension'));
                },
        );
    }
}

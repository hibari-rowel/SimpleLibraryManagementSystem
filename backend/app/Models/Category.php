<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'description',
        'created_by',
        'updated_by',
    ];

    // ####################### Custom Functions ########################
    public static function getFieldValidations($params): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }

    // ######################### Relationships #########################

    // ##################### Accessors & Mutators ######################
}

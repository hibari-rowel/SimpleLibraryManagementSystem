<?php

namespace App\Models;

use App\Enum\BorrowingStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Enum;

class Borrowing extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'borrowings';

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'created_by',
        'updated_by',
    ];

    // ####################### Custom Functions ########################
    public static function getFieldValidations($params): array
    {
        return [
            'name' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'borrow_date' => ['required', 'date'],
            'due_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date'],
            'status' => ['required', new Enum(BorrowingStatus::class)],
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

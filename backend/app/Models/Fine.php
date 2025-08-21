<?php

namespace App\Models;

use App\Enum\FineStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Enum;

class Fine extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'fines';

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'borrowing_id',
        'fine_amount',
        'status',
        'payment_date',
        'created_by',
        'updated_by',
    ];

    // ####################### Custom Functions ########################
    public static function getFieldValidations($params): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'borrowing_id' => ['required', 'exists:borrowings,id'],
            'fine_amount' => ['required', 'decimal:2'],
            'status' => ['required', new Enum(FineStatus::class)],
            'payment_date' => ['nullable', 'date'],
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

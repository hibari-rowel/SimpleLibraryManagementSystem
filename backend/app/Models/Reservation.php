<?php

namespace App\Models;

use App\Enum\ReservationStatus;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rules\Enum;

class Reservation extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'reservations';

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'book_id',
        'reservation_date',
        'status',
        'created_by',
        'updated_by',
    ];

    // ####################### Custom Functions ########################
    public static function getFieldValidations($params): array
    {
        return [
            'description' => ['nullable', 'string'],
            'user_id' => ['required', 'exists:users,id'],
            'book_id' => ['required', 'exists:books,id'],
            'reservation_date' => ['required', 'date'],
            'status' => ['required', new Enum(ReservationStatus::class)],
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

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\UserTypes;
use App\Enum\MembershipTypes;
use App\Enum\UserStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\Rules\Password;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasUuids;
    use SoftDeletes;

    public $incrementing = false;

    protected $table = 'users';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'phone_number',
        'date_of_birth',
        'street_address',
        'city_address',
        'postal_code_address',
        'state_address',
        'country_address',
        'user_type',
        'membership_type',
        'status',
        'password',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'deleted_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'email_verified_at',
    ];

    protected $appends = [
        'full_name',
        'full_address',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ####################### Custom Functions ########################
    public static function getFieldValidations($params): array
    {
        $userID = !empty($params['user_id']) ? $params['user_id'] : null;

        return [
            'first_name' => ['required', 'string'],
            'middle_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($userID)],
            'phone_number' => ['required', 'string'],
            'date_of_birth' => ['required', 'date'],
            'street_address' => ['required', 'string'],
            'city_address' => ['required', 'string'],
            'postal_code_address' => ['required', 'string'],
            'state_address' => ['required', 'string'],
            'country_address' => ['required', 'string'],
            'user_type' => ['required', new Enum(UserTypes::class)],
            'membership_type' => ['required', new Enum(MembershipTypes::class)],
            'status' => ['required', new Enum(UserStatus::class)],
            'password' => ['required', 'string', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'confirm_password' => ['required', 'same:password'],
        ];
    }

    public static function getValidationMessages()
    {
        return [

        ];
    }

    // ######################### Relationships #########################

    // ##################### Accessors & Mutators ######################
    protected function fullName(): Attribute
    {
        return new Attribute(
            get: function () {
                    $fullname = [];
                    if (!empty($this->getOriginal('first_name'))) {
                        $fullname[] = $this->getOriginal('first_name');
                    }
                    if (!empty($this->getOriginal('middle_name'))) {
                        $fullname[] = $this->getOriginal('middle_name');
                    }
                    if (!empty($this->getOriginal('last_name'))) {
                        $fullname[] = $this->getOriginal('last_name');
                    }

                    return implode(' ', $fullname);
                },
        );
    }

    protected function fullAddress(): Attribute
    {
        return new Attribute(
            get: function () {
                    $fullAddress = [];
                    if (!empty($this->getOriginal('street_address'))) {
                        $fullAddress[] = $this->getOriginal('street_address');
                    }
                    if (!empty($this->getOriginal('city_address'))) {
                        $fullAddress[] = $this->getOriginal('city_address');
                    }
                    if (!empty($this->getOriginal('postal_code_address'))) {
                        $fullAddress[] = $this->getOriginal('postal_code_address');
                    }
                    if (!empty($this->getOriginal('state_address'))) {
                        $fullAddress[] = $this->getOriginal('state_address');
                    }
                    if (!empty($this->getOriginal('country_address'))) {
                        $fullAddress[] = $this->getOriginal('country_address');
                    }

                    return implode(', ', $fullAddress);
                },
        );
    }
}

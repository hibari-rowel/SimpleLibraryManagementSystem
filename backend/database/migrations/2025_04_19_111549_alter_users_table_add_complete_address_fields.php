<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'country_address')) {
                $table->string('country_address')->after('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'state_address')) {
                $table->string('state_address')->after('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'postal_code_address')) {
                $table->string('postal_code_address')->after('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'city_address')) {
                $table->string('city_address')->after('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'street_address')) {
                $table->string('street_address')->after('address')->nullable();
            }
            if (!Schema::hasColumn('users', 'date_of_birth')) {
                $table->date('date_of_birth')->after('email_verified_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'country_address')) {
                $table->dropColumn('country_address');
            }
            if (Schema::hasColumn('users', 'state_address')) {
                $table->dropColumn('state_address');
            }
            if (Schema::hasColumn('users', 'postal_code_address')) {
                $table->dropColumn('postal_code_address');
            }
            if (Schema::hasColumn('users', 'city_address')) {
                $table->dropColumn('city_address');
            }
            if (Schema::hasColumn('users', 'street_address')) {
                $table->dropColumn('street_address');
            }
            if (Schema::hasColumn('users', 'date_of_birth')) {
                $table->dropColumn('date_of_birth');
            }
        });
    }
};

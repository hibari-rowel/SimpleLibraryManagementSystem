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
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'image_mime_type')) {
                $table->string('image_mime_type', 50)->after('shelf_location')->nullable();
            }

            if (!Schema::hasColumn('books', 'image_extension')) {
                $table->string('image_extension', 50)->after('shelf_location')->nullable();
            }

            if (!Schema::hasColumn('books', 'original_image_name')) {
                $table->string('original_image_name')->after('shelf_location')->nullable();
            }

            if (!Schema::hasColumn('books', 'image_name')) {
                $table->string('image_name',40)->after('shelf_location')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'image_name')) {
                $table->dropColumn('image_name');
            }

            if (Schema::hasColumn('books', 'original_image_name')) {
                $table->dropColumn('original_image_name');
            }

            if (Schema::hasColumn('books', 'image_extension')) {
                $table->dropColumn('image_extension');
            }

            if (Schema::hasColumn('books', 'image_mime_type')) {
                $table->dropColumn('image_mime_type');
            }
        });
    }
};

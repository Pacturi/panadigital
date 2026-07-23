<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('slug');
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->unique('slug');
        });

        Schema::table('category_attribute', function (Blueprint $table) {
            $table->unique(['category_id', 'attribute_id']);
        });
    }

    public function down(): void
    {
        Schema::table('category_attribute', function (Blueprint $table) {
            $table->dropUnique(['category_id', 'attribute_id']);
        });

        Schema::table('attributes', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->foreignId('tenant_id')->after('id')->constrained();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->foreignId('tenant_id')->after('id')->constrained();
        });
    }
};

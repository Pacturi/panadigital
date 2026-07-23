<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->text('description')->nullable()->after('slug');
            $table->string('phone')->nullable()->after('description');
            $table->string('template')->nullable()->after('phone');
            $table->boolean('active')->default(true)->after('template');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['description', 'phone', 'template', 'active']);
        });
    }
};

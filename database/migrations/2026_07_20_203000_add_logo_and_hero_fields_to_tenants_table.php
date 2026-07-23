<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('instagram');
            $table->string('hero_title')->nullable()->after('banner_path');
            $table->string('hero_subtitle')->nullable()->after('hero_title');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'hero_title', 'hero_subtitle']);
        });
    }
};

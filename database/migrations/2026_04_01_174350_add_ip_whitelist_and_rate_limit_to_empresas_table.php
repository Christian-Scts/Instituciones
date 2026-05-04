<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->json('ip_whitelist')->nullable()->after('url_base_api');
            $table->unsignedInteger('rate_limit_per_minute')->default(60)->after('ip_whitelist');
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn(['ip_whitelist', 'rate_limit_per_minute']);
        });
    }
};
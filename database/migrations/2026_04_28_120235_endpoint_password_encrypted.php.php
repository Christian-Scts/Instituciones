<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            if (!Schema::hasColumn('empresas', 'endpoint_password_encrypted')) {
                $table->text('endpoint_password_encrypted')
                    ->nullable()
                    ->after('endpoint_password_hash');
            }
        });
    }

    public function down(): void
    {
        Schema::table('empresas', function (Blueprint $table) {
            if (Schema::hasColumn('empresas', 'endpoint_password_encrypted')) {
                $table->dropColumn('endpoint_password_encrypted');
            }
        });
    }
};
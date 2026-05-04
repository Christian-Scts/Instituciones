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
        Schema::create('menu_elementos', function (Blueprint $table) {
    $table->id();
    $table->foreignId('menu_grupo_id')->constrained('menu_grupos')->cascadeOnDelete();

    $table->string('titulo');
    $table->string('slug')->unique();
    $table->string('route_name')->nullable();
    $table->string('url')->nullable();
    $table->string('icono')->nullable();
    $table->string('permission_slug')->nullable();

    $table->integer('orden')->default(0);
    $table->boolean('activo')->default(true);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_elementos');
    }
};

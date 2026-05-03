<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambah kolom hero_image (gambar utama)
            $table->string('hero_image')->nullable()->after('type');
            
            // Tambah kolom images (multiple images) - optional
            $table->json('images')->nullable()->after('hero_image');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'images']);
        });
    }
};
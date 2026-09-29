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
        Schema::create('wp_import_mapping', function (Blueprint $table) {
            $table->id();
            $table->string('wp_type', 60)->index(); // post | page | category | tag
            $table->unsignedBigInteger('wp_id')->index();
            $table->string('wp_slug', 250)->nullable();
            $table->unsignedBigInteger('local_id')->nullable()->index();
            $table->string('local_type', 191)->nullable();
            $table->string('status', 20)->default('imported'); // imported | skipped | failed
            $table->text('error')->nullable();
            $table->timestamps();

            $table->unique(['wp_type', 'wp_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wp_import_mapping');
    }
};

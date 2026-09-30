<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasTable('index_now_logs')) {
            Schema::create('index_now_logs', function (Blueprint $table): void {
                $table->id();
                $table->string('url', 500);
                $table->integer('status_code')->default(0);
                $table->string('message', 500)->nullable();
                $table->boolean('is_manual')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('index_now_logs');
    }
};

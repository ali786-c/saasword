<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('post_sync_logs')) {
            return;
        }

        Schema::create('post_sync_logs', function (Blueprint $table): void {
            $table->id();

            // Where the payload came from — the sender's own post id.
            $table->unsignedBigInteger('local_post_id')->nullable()->index();

            // Where it landed on this site (null when nothing was written).
            $table->unsignedBigInteger('post_id')->nullable()->index();

            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable()->index();

            // created | updated | rejected | failed
            $table->string('action', 32)->default('failed')->index();

            $table->unsignedSmallInteger('http_status')->default(0);
            $table->boolean('success')->default(false)->index();

            // Human-readable outcome: the validation message, the auth reason,
            // or the exception text. Never the payload itself.
            $table->string('message', 500)->nullable();

            $table->string('token_name', 100)->nullable();
            $table->unsignedInteger('duration_ms')->nullable();

            $table->timestamps();

            $table->index(['success', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_sync_logs');
    }
};

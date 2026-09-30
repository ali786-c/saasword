<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('index_now_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('index_now_logs', 'engine')) {
                $table->string('engine', 20)->default('indexnow')->after('is_manual');
            }

            if (! Schema::hasColumn('index_now_logs', 'action')) {
                $table->string('action', 20)->default('update')->after('engine');
            }
        });
    }

    public function down(): void
    {
        Schema::table('index_now_logs', function (Blueprint $table): void {
            foreach (['engine', 'action'] as $column) {
                if (Schema::hasColumn('index_now_logs', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};

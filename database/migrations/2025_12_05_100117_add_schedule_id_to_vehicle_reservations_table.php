<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('vehicle_reservations', function (Blueprint $table) {
            // スケジュールとの紐付け用（スケジュールが消えたら予約も消える）
            $table->foreignId('schedule_id')
                ->nullable()
                ->after('id')
                ->constrained('schedules')
                ->onDelete('cascade');

            // スケジュール側に情報があるので、こちらはnullableに変更
            $table->string('purpose')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicle_reservations', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');

            // 元に戻す（nullデータがあるとエラーになるかも）
            $table->string('purpose')->nullable(false)->change();
        });
    }
};

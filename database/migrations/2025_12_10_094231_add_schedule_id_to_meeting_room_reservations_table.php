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
        Schema::table('meeting_room_reservations', function (Blueprint $table) {
            // 行先予定との紐づけ用カラムを追加
            // 既存データや紐づけない予約も考慮してnullable
            // スケジュールが削除されたら予約も消えるようcascade
            $table->foreignId('schedule_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->onDelete('cascade');

            // スケジュール側で目的などを管理するため、予約側のtitleはnullableに変更
            $table->string('title')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('meeting_room_reservations', function (Blueprint $table) {
            // ロールバック時は外部キー制約を外してからカラムを削除
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');

            // title を元に戻す（注意: nullデータがあるとエラーになる可能性があります）
            $table->string('title')->nullable(false)->change();
        });
    }
};

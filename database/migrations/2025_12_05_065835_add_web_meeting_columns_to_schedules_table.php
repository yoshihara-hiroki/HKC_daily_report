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
        Schema::table('schedules', function (Blueprint $table) {
            // Web会議用のカラムを追加
            $table->boolean('is_web_meeting')->default(false)->after('destination')->comment('Web会議フラグ');
            $table->string('meeting_type')->nullable()->after('is_web_meeting')->comment('会議ツール種別');
            $table->string('meeting_url')->nullable()->after('meeting_type')->comment('会議URL');
            $table->text('participants_memo')->nullable()->after('meeting_url')->comment('参加者・メモ');
        });

        // 不要になったweb_meetingsテーブルを削除
        Schema::dropIfExists('web_meetings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn([
                'is_web_meeting',
                'meeting_type',
                'meeting_url',
                'participants_memo',
            ]);
        });

        // web_meetingsテーブルの復元定義
        Schema::create('web_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('meeting_date'); // 注: 元の仕様に合わせて定義
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('meeting_type');
            $table->string('title');
            $table->string('meeting_url')->nullable();
            $table->text('participants_memo')->nullable();
            $table->timestamps();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedEnvelopeHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('red_envelope_history', function (Blueprint $table) {
            $table->id(); // 自增 ID
            $table->unsignedBigInteger('user_id'); // 外鍵，指向 users 資料表
            $table->integer('attempt_count'); // 當次搶紅包的次數
            $table->timestamp('grab_timestamp'); // 當次搶紅包的時間戳
            $table->decimal('amount', 8, 2); // 當次搶紅包的金額

            $table->timestamps(); // 自動生成 created_at 和 updated_at 欄位

            // 設置外鍵約束
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('red_envelope_history');
    }
}

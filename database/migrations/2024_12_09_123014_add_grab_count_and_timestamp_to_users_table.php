<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGrabCountAndTimestampToUsersTable extends Migration
{
  
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // 新增搶紅包次數
            $table->integer('grab_count')->default(0)->after('red_envelope_amount');
            // 新增最後一次搶紅包的時間，預設為null
            $table->timestamp('last_grab_timestamp')->nullable()->after('grab_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}

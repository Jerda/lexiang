<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConcernInviteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concern_invite', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('邀请人ID');
            $table->integer('invitees_user_id')->comment('被邀请人ID');
            $table->tinyInteger('status')->commont('状态');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concern_invite');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_key_id')->nullable();
            $table->string('access_key_secret')->nullable();
            $table->string('sign_name')->nullable();
            $table->string('api_key')->nullable();
            $table->string('app_id')->nullable();
            $table->string('app_key')->nullable();
            $table->string('project')->nullable();
            $table->string('account_sid')->nullable();
            $table->string('account_token')->nullable();
            $table->string('is_sub_account')->nullable();
            $table->string('sms_user')->nullable();
            $table->string('sms_key')->nullable();
            $table->string('timestamp')->nullable();
            $table->string('ak')->nullable();
            $table->string('sk')->nullable();
            $table->string('domain')->nullable();
            $table->string('user_id')->nullable();
            $table->string('password')->nullable();
            $table->string('account')->nullable();
            $table->string('ip')->nullable();
            $table->string('ext_no')->nullable();
            $table->string('username')->nullable();
            $table->string('gwid')->nullable();
            $table->string('from')->nullable();
            $table->string('token')->nullable();
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
        Schema::dropIfExists('sms');
    }
}

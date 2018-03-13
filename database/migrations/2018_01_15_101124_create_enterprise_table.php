<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnterpriseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enterprise', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->defalut('')->comment('企业名称');
            $table->string('legal_person')->defalut('')->comment('企业法人');
            $table->string('linker')->defalut('')->comment('联系人');
            $table->string('linker_mobile')->defalut('')->comment('联系人手机号');
            $table->string('linker_email')->defalut('')->comment('邮箱');
            $table->string('enterprise_type_id')->defalut('')->comment('行业类型');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('district')->nullable()->comment('县');
            $table->string('address')->defalut('')->comment('详情地址');
            $table->tinyInteger('status')->defalut(0)->comment('状态');
            $table->timestamps();
        });

        Schema::create('admin_enterprise', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('enterprise_id');
            $table->tinyInteger('main')->default(0);
            $table->timestamps();
        });

        Schema::create('enterprise_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
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
        Schema::dropIfExists('enterprise');
    }
}

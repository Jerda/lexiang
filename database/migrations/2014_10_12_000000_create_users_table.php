<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 60)->unique()->nullable()->comment('用户名');
            $table->string('email', 60)->unique()->nullable()->comment('邮箱');
            $table->string('mobile', 15)->unique()->nullable()->comment('手机号');
            $table->string('password', 60)->default('');
            $table->tinyInteger('status')->default(0)->comment('状态');
            $table->string('name', 20)->default('')->comment('姓名');
            $table->string('sex', 1)->default('')->comment('性别');

            /*--可选字段--*/
            $table->string('birthday', 13)->nullable()->comment('出生日期');
            $table->string('user_num', 60)->nullable()->default('')->comment('会员编号');
            $table->tinyInteger('type')->default(0)->comment('用户类型');
            $table->string('idcard', 30)->nullable()->default('')->comment('身份证号码');
            $table->text('idcard_img', 60)->nullable()->comment('身份证照片');
            $table->string('qq', 20)->nullable()->default('')->comment('QQ号');
            $table->text('remark')->nullable()->comment('备注');
            /*-----------*/

            /*-- 特殊关联字段 --*/
            $table->tinyInteger('is_admin')->default(0)->comment('是否是管理员');
            /*----------------*/
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * wechat账号
         */
        Schema::create('wechat_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60)->nullabe()->default('')->comment('公众号名称');
            $table->string('wechat_id', 60)->nullabe()->default('')->comment('微信号');
            $table->string('init_wechat_id', 60)->nullabe()->default('')->comment('微信号原始ID');
            $table->string('app_id', 60)->default('')->comment('AppID');
            $table->string('app_secret', 60)->default('')->comment('应用密匙');
            $table->string('api', 60)->nullabe()->default('')->comment('API地址');
            $table->string('token', 60)->default('')->comment('TOKEN');
            $table->string('encoding_aes_key', 60)->nullabe()->default('')->comment('消息加解密密钥');
            $table->string('auth_file', 150)->nullabe()->default('')->comment('授权文件');
            $table->string('qr_code', 150)->nullabe()->default('')->comment('二维码');
            $table->string('attention', 150)->nullabe()->default('')->comment('关注连接');
            $table->timestamps();
        });

        /**
         * wechat按钮
         */
        Schema::create('wechat_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 20)->comment('菜单名');
            $table->tinyInteger('parent_id')->default(0)->comment('父菜单ID');
            $table->string('key', 60)->nullabe()->comment('微信KEY');
            $table->string('url', 150)->nullabe()->comment('URL');
            $table->string('key_word', 60)->nullabe()->comment('关键字');
            $table->string('type', 60)->nullabe()->comment('类型');
            $table->string('sort_id', 4)->nullabe()->comment('排序号');
            $table->timestamps();
        });

        /**
         * wechat用户
         */
        Schema::create('wechat_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();

            /*----微信----*/
            $table->string('openid', 150)->default('')->comment('微信OPENID');
            $table->string('nickname', 60)->default('')->commnet('昵称');
//            $table->json('wechat_info')->default()->comment('微信详情');
            $table->text('avatar', 150)->nullable()->comment('头像');
            $table->string('qrcode', 150)->default('')->commment('个人二维码');
//            $table->string('wechat_username')->default()->comment('微信名');
            $table->tinyInteger('sex')->default(0)->comment('姓名');
            $table->string('country', 15)->default('')->comment('国家');
            $table->string('province', 15)->default('')->comment('省份');
            $table->string('city', 15)->default('')->comment('城市');
            $table->timestamp('subscribe_time')->comment('用户关注公众号时间');
            $table->text('wechat_remark')->nullable()->comment('用户备注');
            $table->tinyInteger('groupid')->default()->comment('用户所在的分组ID');
            $table->string('tagid_list')->default('')->comment('用户被打上的标签ID列表');
            /*-----------*/

            /*--项目特殊需求字段--*/
            /*------------------*/
            $table->foreign('user_id')->references('id')->on('users');
        });

        /**
         * wechat用户组
         */
        /*Schema::create('wechat_user_group', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('group_id')->comment('分组ID');
            $table->string('name')->comment('分组名称');
            $table->tinyInteger('count')->default(0)->comment('用户总数');
        });*/

        /**
         * wechat回复
         */
        Schema::create('wechat_callback', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 60)->default('')->comment('消息类型');
            $table->string('key_word', 60)->default('')->comment('关键字');
            $table->string('title')->default('')->comment('标题');
            $table->text('content')->nullable()->comment('内容');
            $table->text('no_normal_content')->nullable()->comment('受限回复');
            $table->string('img', 150)->default('')->comment('图片');
            $table->string('link', 150)->default('')->comment('链接');
            $table->timestamps();
        });

        /**
         * wechat素材
         */
        Schema::create('wechat_material', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type', 150)->default('')->comment('类型');
            $table->string('desc')->default('')->comment('描述');
            $table->string('path', 150)->default('')->comment('路径');
            $table->string('media_id', 150)->default('');
            $table->string('url', 150)->default('');
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
        Schema::dropIfExists('wechat_account');

        Schema::dropIfExists('wechat_menu');

        Schema::dropIfExists('user_wechat');

        Schema::dropIfExists('wechat_user_group');

        Schema::dropIfExists('wechat_callback');

        Schema::dropIfExists('wechat_material');
    }
}

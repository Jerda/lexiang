<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_name', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id');
            $table->string('name')->comment('权限名称');
//            $table->tinyInteger('level')->comment('所属路由级别'); // url = api/user/all  user属于1级
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
        Schema::dropIfExists('permission_name');
    }
}

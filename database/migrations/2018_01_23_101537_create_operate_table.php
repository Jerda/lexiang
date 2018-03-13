<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('operate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type')->comment('操作类型');
            $table->string('content')->comment('内容');
            $table->integer('operator')->comment('操作人');
            $table->string('model')->nullable()->comment('模型');
            $table->integer('model_id')->nullable()->comment('模型对应ID');
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
        Schema::dropIfExists('operate');
    }
}

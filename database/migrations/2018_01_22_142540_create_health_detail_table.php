<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHealthDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * 超声骨密度分析仪
         */
        Schema::create('CSGMDFXY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('T');
            $table->string('Z');
            $table->string('BQI');
            $table->string('adult_percent');
            $table->string('age_percent');
            $table->string('PAB');
            $table->string('RRF');
            $table->string('EOA');
        });
        /**
         * 动脉硬化检测仪
         */
        Schema::create('DMYHJCY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('PWV');
            $table->string('ABI');
            $table->string('ASI');
            $table->string('C1');
            $table->string('C2');
            $table->string('SV');
            $table->string('CO');
            $table->string('SVR');
            $table->string('ET');
//            $table->string('zuo_shang_zhi_shou_suo_ya');
//            $table->string('zuo_shang_zhi_shu_zhang_ya');
            //未完
            $table->string('MAP');
            $table->string('PP');
            $table->string('HR');
            $table->string('BMI');
        });
        /**
         * 人体成分分析仪
         */
        Schema::create('RTCFFXY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('wu_ji_yan');
            $table->string('zhi_fang_zhong');
            $table->string('dan_bai_zhi');
            $table->string('ji_rou_zhong');
            $table->string('qu_zhi_ti_zhong');
            $table->string('xi_bao_wai_ye');
            $table->string('xi_bao_nei_ye');
            $table->string('shen_ti_shui_fen');
            $table->string('shi_ji_ti_zhong');
            $table->string('fu_zhong_zhi_shu');
            $table->string('ti_zhong_zhi_shu');
            $table->string('ti_zhi_fang_lv');
        });
        /**
         * 十二导心电图机
         */
        Schema::create('SEDXDTJ', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('xin_fang_fei_da');
            $table->string('xin_shi_fei_da');
            $table->string('xin_ji_que_xue');
            $table->string('xin_ji_geng_si');
            $table->string('xin_lv_shi_chang');
            $table->string('dou_xing_xin_lv_shi_chang');
            $table->string('qi_qian_shou_suo');
            $table->string('yi_wei_xin_dong_guo_su');
            $table->string('pu_dong_yu_chan_dong');
            $table->string('ji_xing_xin_ji_geng_se');
            $table->string('chen_jiu_xin_ji_geng_se');
            $table->string('xin_ji_que_yang');
            $table->string('fei_te_yi_xing_ST_T_bian_hua');
            $table->string('xin_lv_bu_zheng');
            $table->string('dou_xing_xin_bo_guo_huan');
            $table->string('dou_xing_xin_bo_guo_su');
        });
        /**
         * 身高体重仪
         */
        Schema::create('SGTZY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('shen_gao');
            $table->string('ti_zhong');
        });
        /**
         * 血脂分析仪
         */
        Schema::create('XZFXY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('zong_dan_gu_chun');
            $table->string('gao_mi_du_zhi_dan_bai_dan_gu_chun');
            $table->string('gan_you_san_zhi');
            $table->string('di_mi_du_dan_gu_chun');
        });
        /**
         * 红外体温仪
         */
        Schema::create('HWTWY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('ti_wen');
            $table->string('biao_mian_wen_du');
        });
        /**
         * 血氧仪
         */
        Schema::create('XYY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('spo2');
        });
        /**
         * 血糖仪
         */
        Schema::create('XTY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('xue_tang_zhi');
        });
        /**
         * 尿液分析仪
         */
        Schema::create('NYFXY', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('health_id');
            $table->string('bai_xi_bao');
            $table->string('ya_xiao_suan_yan');
            $table->string('niao_dan_yuan');
            $table->string('dan_bao_zhi');  //重复
            $table->string('PH');
            $table->string('qian_xue');
            $table->string('bi_zhong');
            $table->string('tong_ti');
            $table->string('dan_hong_su');
            $table->string('pu_tao_tang');
            $table->string('wei_sheng_su_C');
        });

        /*Schema::create('health_detail_1', function (Blueprint $table) {
            $table->increments('id');
            $table->string('T');
            $table->string('Z');
            $table->string('BQI');
            $table->string('adult_percent');
            $table->string('age_percent');
            $table->string('PAB');
            $table->string('RRF');
            $table->string('EOA');
            $table->string('PWV');
            $table->string('ABI');
            $table->string('ASI');
            $table->string('C1');
            $table->string('C2');
            $table->string('SV');
            $table->string('CO');
            $table->string('SVR');
            $table->string('ET');
//            $table->string('zuo_shang_zhi_shou_suo_ya');
//            $table->string('zuo_shang_zhi_shu_zhang_ya');
            //未完
            $table->string('MAP');
            $table->string('PP');
            $table->string('HR');
            $table->string('BMI');
            $table->string('wu_ji_yan');
            $table->string('zhi_fang_zhong');
            $table->string('dan_bai_zhi');
            $table->string('ji_rou_zhong');
            $table->string('qu_zhi_ti_zhong');
            $table->string('xi_bao_wai_ye');
            $table->string('xi_bao_nei_ye');
            $table->string('shen_ti_shui_fen');
            $table->string('shi_ji_ti_zhong');
            $table->string('fu_zhong_zhi_shu');
            $table->string('ti_zhong_zhi_shu');
            $table->string('ti_zhi_fang_lv');

            $table->string('bai_xi_bao');
            $table->string('ya_xiao_suan_yan');
            $table->string('niao_dan_yuan');
//            $table->string('dan_bao_zhi');  //重复
            $table->string('PH');
            $table->string('qian_xue');
            $table->string('bi_zhong');
            $table->string('tong_ti');
            $table->string('dan_hong_su');
            $table->string('pu_tao_tang');
            $table->string('wei_sheng_su_C');
            $table->timestamps();
        });

        Schema::create('health_detail_2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('xin_fang_fei_da');
            $table->string('xin_shi_fei_da');
            $table->string('xin_ji_que_xue');
            $table->string('xin_ji_geng_si');
            $table->string('xin_lv_shi_chang');
            $table->string('dou_xing_xin_lv_shi_chang');
            $table->string('qi_qian_shou_suo');
            $table->string('yi_wei_xin_dong_guo_su');
            $table->string('pu_dong_yu_chan_dong');
            $table->string('ji_xing_xin_ji_geng_se');
            $table->string('chen_jiu_xin_ji_geng_se');
            $table->string('xin_ji_que_yang');
            $table->string('fei_te_yi_xing_ST_T_bian_hua');
            $table->string('xin_lv_bu_zheng');
            $table->string('dou_xing_xin_bo_guo_huan');
            $table->string('dou_xing_xin_bo_guo_su');

            $table->string('shen_gao');
            $table->string('ti_zhong');
            $table->string('zong_dan_gu_chun');
            $table->string('gao_mi_du_zhi_dan_bai_dan_gu_chun');
            $table->string('gan_you_san_zhi');
            $table->string('di_mi_du_dan_gu_chun');

            $table->string('ti_wen');
            $table->string('biao_mian_wen_du');

            $table->string('spo2');
            $table->string('xue_tang_zhi');
            $table->timestamps();
        });*/
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('CSGMDFXY');
        Schema::dropIfExists('DMYHJCY');
        Schema::dropIfExists('RTCFFXY');
        Schema::dropIfExists('SEDXDTJ');
        Schema::dropIfExists('SGTZY');
        Schema::dropIfExists('XZFXY');
        Schema::dropIfExists('HWTWY');
        Schema::dropIfExists('XYY');
        Schema::dropIfExists('XTY');
        Schema::dropIfExists('NYFXY');
    }
}

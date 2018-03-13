<?php

namespace App\Libraries\SMS;

use Overtrue\EasySms\EasySms;

class SMS
{
    private $config = [
        // HTTP 请求的超时时间（秒）
        'timeout'  => 5.0,

        // 默认发送配置
        'default'  => [
            // 网关调用策略，默认：顺序调用
            'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

            // 默认可用的发送网关
            'gateways' => [
                'yunpian', 'aliyun', 'alidayu',
            ],
        ],
        // 可用的网关配置
        'gateways' => [
            'errorlog' => [
                'file' => '/tmp/easy-sms.log',
            ],
            'yunpian'  => [
                'api_key' => '824f0ff2f71cab52936axxxxxxxxxx',
            ],
            'aliyun'   => [
                'access_key_id'     => '',
                'access_key_secret' => '',
                'sign_name'         => '',
            ],
            'alidayu'  => [
                //...
            ],
        ],
    ];


    private $service_provider = [
        'aliyun' => '阿里云',
        'yunpian' => '云片',
        'submail' => 'Submail',
        'luosimao' => '螺丝帽',
//        'yuntongxun' => '容联云通讯',
        'huyi' => '互亿无线',
        'juhe' => '聚合数据',
//        'sendcloud' => 'SendCloud',
        'baidu' => '百度云',
        'huaxin' => '华信短信',
        'chuanglan' => '253云通讯（创蓝）',
        'rongcloud' => '融云',
        'tianyiwuxian' => '天毅无线',
        'twilio' => 'twilio',
    ];


    private $gateway_rules = [
        //阿里云
        'aliyun'       => ['access_key_id', 'access_key_secret', 'sign_name'],
        //云片
        'yunpian'      => ['api_key'],
        //Submail
        'submail'      => ['app_id', 'app_key', 'project'],
        //螺丝帽
        'luosimao'     => ['api_key'],
        //容联云通讯
//        'yuntongxun'   => ['app_id', 'account_sid', 'account_token', 'is_sub_account' => false],
        //互亿无线
        'huyi'         => ['api_id', 'api_key'],
        //聚合数据
        'juhe'         => ['app_key'],
        //SendCloud
//        'sendcloud'    => ['sms_user', 'sms_key', 'timestamp' => false],// 是否启用时间戳
        //百度云
        'baidu'        => ['ak', 'sk', 'invoke_id', 'domain'],
        //华信短信
        'huaxin'       => ['user_id', 'password', 'account', 'ip', 'ext_no'],
        //253云通讯（创蓝）
        'chuanglan'    => ['username', 'password'],
        //融云
        'rongcloud'    => ['app_key', 'app_secret'],
        //天毅无线
        'tianyiwuxian' => ['username', 'password', 'gwid'],
        //twilio
        'twilio'       => ['account_sid', 'from', 'token']
        ];


    /*private $gateway_rules = [
        //阿里云
        'aliyun' => [
            'access_key_id' => '',
            'access_key_secret' => '',
            'sign_name' => '',
        ],
        //云片
        'yunpian' => [
            'api_key' => '',
        ],
        //Submail
        'submail' => [
            'app_id' => '',
            'app_key' => '',
            'project' => '',
        ],
        //螺丝帽
        'luosimao' => [
            'api_key' => '',
        ],
        //容联云通讯
        'yuntongxun' => [
            'app_id' => '',
            'account_sid' => '',
            'account_token' => '',
            'is_sub_account' => false,
        ],
        //互亿无线
        'huyi' => [
            'api_id' => '',
            'api_key' => '',
        ],
        //聚合数据
        'juhe' => [
            'app_key' => '',
        ],
        //SendCloud
        'sendcloud' => [
            'sms_user' => '',
            'sms_key' => '',
            'timestamp' => false, // 是否启用时间戳
        ],
        //百度云
        'baidu' => [
            'ak' => '',
            'sk' => '',
            'invoke_id' => '',
            'domain' => '',
        ],
        //华信短信
        'huaxin' => [
            'user_id'  => '',
            'password' => '',
            'account'  => '',
            'ip'       => '',
            'ext_no'   => '',
        ],
        //253云通讯（创蓝）
        'chuanglan' => [
            'username'  => '',
            'password' => '',
        ],
        //融云
        'rongcloud' => [
            'app_key' => '',
            'app_secret' => '',
        ],
        //天毅无线
        'tianyiwuxian' => [
            'username' => '', //用户名
            'password' => '', //密码
            'gwid' => '', //网关ID
        ],
        //twilio
        'twilio' => [
            'account_sid' => '', // sid
            'from' => '', // 发送的号码 可以在控制台购买
            'token' => '', // apitoken
        ],
    ];*/

    private $sms;

    /**
     * SMS constructor.
     * @param array $config
     */
    /*public function __construct(array $config)
    {
        $sms = new EasySms($config);
    }*/


    public function options()
    {

    }


    public function rulesForServiceProvider($service_provider)
    {
        return $this->gateway_rules[$service_provider];
    }


    /**
     * 获取服务提供商
     * @return array
     */
    public function serviceProviders()
    {
        return $this->service_provider;
    }
}
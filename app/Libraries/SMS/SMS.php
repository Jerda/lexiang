<?php

namespace App\Libraries\SMS;

use Overtrue\EasySms\EasySms;

class SMS
{
    //Interface URL Used to send SMS
    const API_SEND_URL = 'http://intapi.253.com/send/json?';
    //Interface URL Used to Query SMS balance
    const API_BALANCE_QUERY_URL = 'http://intapi.253.com/balance/json?';

    const API_ACCOUNT = 'I3442624';//Get SMS Account  from  https://zz.253.com/site/login.html

    const API_PASSWORD ='Oqowe1sxC';//Get SMS Password  from https://zz.253.com/site/login.html

    /**
     * 发送短信
     * @param $mobile
     * @param $message
     * @return mixed
     */
    public function send($mobile, $message) {
        //创蓝接口参数
        $postArr = array (
            'account'  =>  self::API_ACCOUNT,
            'password' => self::API_PASSWORD,
            'msg' => $message,
            'mobile' => '86'.$mobile
        );

        $result = $this->curlPost( self::API_SEND_URL , $postArr);
        return $result;
    }


    /**
     * 通过CURL发送HTTP请求
     * @param string $url  //请求URL
     * @param array $postFields //请求参数
     * @return mixed
     */
    private function curlPost($url,$postFields){
        $postFields = json_encode($postFields);
        $ch = curl_init ();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8'
            )
        );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POST, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt( $ch, CURLOPT_TIMEOUT,1);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec ( $ch );
        if (false == $ret) {
            $result = curl_error(  $ch);
        } else {
            $rsp = curl_getinfo( $ch, CURLINFO_HTTP_CODE);
            if (200 !== $rsp) {
                $result = "请求状态 ". $rsp . " " . curl_error($ch);
            } else {
                $result = $ret;
            }
        }
        curl_close ( $ch );
        return $result;
    }

}

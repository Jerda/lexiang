<?php

namespace App\Libraries\Wechat;

use App\Model\User;
use EasyWeChat\Factory;
use App\Model\Wechat\WechatUser;
use App\Model\Wechat\WechatAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
//use App\Libraries\WechatTool\Material;

class WechatTool
{
    /*
    |--------------------------------------------------------------------------
    | 微信帮助类
    |--------------------------------------------------------------------------
    */
//    use Material;

    protected $options = [
        'debug'   => true,
        /**
         * 账号基本信息，请从微信公众平台/开放平台获取
         */
        'app_id'  => '',         // AppID
        'secret'  => '',         // AppSecret
        'token'   => '',         // Token
        'aes_key' => '',         // EncodingAESKey，安全模式下请一定要填写！！！

        /**
         * 日志配置
         *
         * level: 日志级别, 可选为：
         *         debug/info/notice/warning/error/critical/alert/emergency
         * permission：日志文件权限(可选)，默认为null（若为null值,monolog会取0644）
         * file：日志文件位置(绝对路径!!!)，要求可写权限
         */
        'log'     => [
            'level'      => 'debug',
            'permission' => 0777,
            'file'       => '/usr/share/nginx/html/environmental_detection/storage/logs/easywechat.log',
        ],
        'oauth'   => [
            'scopes'   => ['snsapi_userinfo'],
            'callback' => 'http://tsq.zhouzichao.com/wechat/oauth_callback',
        ],
    ];

    //支付配置
    protected $pay_config = [
        // 必要配置
        'app_id'             => 'wxe3ffa1964379de5e',
        'mch_id'             => '1483526362',
        'key'                => 'jwily1314jwily1314jwily1314jwily',   // API 密钥

        // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
        'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
        'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

        'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
        'sandbox' => true,
    ];

    protected $menu_role = [
        'level_one_max' => 3, //一级菜单最多数量
        'level_two_max' => 5, //二级菜单最多数量
    ];

    protected $get_users_max = 10000; //每次微信允许获取用户最大值

    private $userService;

    private $app;


    public function __construct()
    {
        $this->getOptions();

        $this->app = Factory::officialAccount($this->options);

        $this->userService = $this->app->user;
    }


    /**
     * 用户交互服务
     */
    public function server()
    {
        $server = $this->app->server;

        $server->push(function ($message) {

            switch ($message['MsgType']) {
                case 'event':
                    return $this->event($message);
                    break;
                case 'text':
                    return '收到文本消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        return $server->serve();
    }


    /**
     * 用户事件处理
     * @param $message
     * @return mixed
     */
    private function event($message)
    {
        switch ($message['Event']) {
            case 'subscribe' :        //关注
                $this->addUser($message['FromUserName']);

                return trans('system.wechat_welcome');

                break;
            case 'unsubscribe' :      //取消关注
                $this->delUser($message['FromUserName']);

                break;
            case 'CLICK' :            //button点击事件
//                return 12;
                session(['user_id' => 5]);
                return session('user_id');
                break;
        }
    }


    /**
     * 将微信返回的错误码转换为错误信息
     * @param $code
     * @return mixed|string
     */
    public function getErrorMessage($code)
    {
        try {
            return trans('wechat.' . $code);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * 获取用户列表
     * @param
     * @return mixed
     */
    public function getUserList($nextOpenId = null)
    {
        return $this->userService->list($nextOpenId = null);
    }


    /**
     * 获取用户微信信息
     * @param $openIds
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function getUserWechat($openIds)
    {
        if (is_array($openIds)) {
            return $this->userService->select($openIds);
        } else {
            return $this->userService->get($openIds);
        }
    }


    /**
     * 添加微信用户
     * @param $openid
     */
    public function addUser($openid)
    {
        $user_wechat = $this->getUserWechat($openid);

        $User = new User();

        $User::setWechatData($this->formatUserDetailData($user_wechat));

        $User::create(['username' => $openid, 'password' => bcrypt($openid)]);
    }


    /**
     * 拉黑用户
     * @param $openid
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function blockUser($openid)
    {
        return $this->app->user->block($openid);
    }


    /**
     * 取消拉黑
     * @param $openid
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function unblockUser($openid)
    {
        return $this->app->user->unblock($openid);
    }



    public function getWechatUser()
    {
        return $this->app->oauth->user();
    }


    /**
     * 通过openid获取用户
     * @param $openid
     * @return mixed
     */
    public function getUserByOpenID($openid)
    {
        $user = User::with('wechat')->whereHas('wechat', function ($query) use ($openid) {
                $query->where('openid', $openid);
            })->first();

        return empty($user) ? null : $user;
    }


    public function getOpenIDByUserID($user_id)
    {
        $user = User::where('id', $user_id)->with('wechat')->first();

        return $user->wechat->openid;
    }


    /**
     * 通过openid获取用户ID
     * @param $openid
     * @return mixed
     */
    public function getUserIdByOpenID($openid)
    {
        $user_detail = WechatUser::where('openid', $openid)->first();

        return empty($user_detail) ? null : $user_detail->user()->first()->id;
    }


    /**
     * 获取微信按钮规则
     * @param $name
     * @return array|int
     */
    public function getMenuRole($name = '')
    {
        if ($name) {
            return $this->menu_role[$name];
        }

        return $this->menu_role;
    }


    /**
     * 发布菜单
     * @param Model $model
     * @return bool
     */
    public function issueMenus(Model $model)
    {
        $menu = $this->app->menu;

        $res = $menu->create($this->convertMenu($model));

        if ($res['errcode'] != 0) {
            return $this->getErrorMessage($res['errcode']);
        } else {
            return true;
        }
    }


    /**
     * 获取网页授权地址
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function getOauthURL()
    {
        $response =  $this->app->oauth->redirect();

        return response()->json(['data' => $response->getTargetUrl()]);
    }


    /**
     * 网页授权回调
     */
    public function oauthCallback()
    {
        return $this->getUserByOpenID($this->app->oauth->user()->getId());
    }


    /**
     * 获取微信配置
     */
    private function getOptions()
    {
        $account = WechatAccount::first();

        if (!empty($account)) {
            $this->options['app_id'] = $account['app_id'];
            $this->options['secret'] = $account['app_secret'];
            $this->options['token'] = $account['token'];
            $this->options['aes_key'] = $account['encoding_aes_key'];
        }
    }


    /**
     * 准备用户的微信数据以便存入数据库
     * @param array $data
     * @return array
     */
    private function formatUserDetailData(array $data)
    {
        $data['tagid_list'] = json_encode($data['tagid_list']);
        $data['subscribe_time'] = date('Y-m-d H:i:s', $data['subscribe_time']);
        $data['avatar'] = $data['headimgurl'];
        return $data;
    }


    /**
     * 将菜单转换成微信接口所需的格式
     * @param $model
     * @return array
     */
    private function convertMenu(Model $model)
    {
        $_menus = [];
        $menus = $model::all()->toArray();
        $menus = $this->treeSortForMenu($menus);

        foreach ($menus as $menu) {
            $_menus[$menu['id']] = $menu;

            if (isset($menu['children'])) {

                foreach ($menu['children'] as $child) {
                    $_menus[$child['parent_id']]['sub_button'][] = $child;
                }

                unset($_menus[$menu['id']]['children']);
            }

        }

        $new = [];
        $index = 0;

        foreach ($_menus as $key => $menu) {

            if (isset($menu['sub_button'])) {
                $new[$index] = ['name' => $menu['name'], 'sub_button' => []];

                foreach ($menu['sub_button'] as $sub) {
                    $add = [
                        'type' => $sub['type'],
                        'name' => $sub['name'],
                    ];

                    if ($add['type'] == 'click') {
                        $add['key'] = $sub['key'];
                    } elseif ($add['type'] == 'view') {
                        $add['url'] = $sub['url'];
                    } else {
                        $add['key'] = $sub['key_word'];
                    }

                    $new[$index]['sub_button'][] = $add;
                }

                $index++;
            } else {
                $add = [
                    'type' => $menu['type'],
                    'name' => $menu['name'],
                ];

                if ($add['type'] == 'click') {
                    $add['key'] = $menu['key_word'];
                } elseif ($add['type'] == 'view') {
                    $add['url'] = $menu['url'];
                } else {
                    $add['key'] = $menu['key_word'];
                }

                $new[] = $add;
                $index++;
            }
        }

        return $new;
    }


    /**
     * 将子菜单装入父菜单中
     *
     * @param $data array
     * @param bool $isShow
     * @param $parent_id  integer   父权限ID，该参数一般不填，主要在递归时用。
     * @param int $sort_id
     * @return array
     */
    private function treeSortForMenu($data, $isShow = false, $parent_id = 0, $sort_id = 0)
    {
        static $arr = [];

        foreach ($data as $value) {

            if ($value['parent_id'] == $parent_id) {

                if ($isShow && $value['parent_id'] != 0) {
                    $value['name'] = '&nbsp;&nbsp;&nbsp;&nbsp;|-----' . $value['name'];
                }

                if ($parent_id == 0) {
                    $arr[$value['sort_id']] = $value;
                } else {
                    $arr[$sort_id]['children'][$value['sort_id']] = $value;
                }

                $this->treeSortForMenu($data, $isShow, $value['id'], $value['sort_id']);
            }
        }

        return $arr;
    }


    /**
     * 获取图文信息
     * @param int $offset
     * @param int $count
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     */
    public function news($offset = 0, $count = 20)
    {
        $res = $this->app->material->list('news', $offset, $count);
        //格式化图文更新时间
        foreach ($res['item'] as &$value) {
            $value['content']['update_time'] = date('Y-m-d', $value['content']['update_time']);
        }

        return $res;
    }



    public function addService($account, $nickname)
    {
        return $this->app->customer_service->create($account, $nickname);
    }


    public function listForService()
    {
        return $this->app->customer_service->list();
    }


    /*--------消息------------*/
    public function uploadImage($path)
    {
        return $this->app->material->uploadImage($path);
    }
}

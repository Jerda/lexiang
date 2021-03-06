import VueRouter from 'vue-router'
import Store from '@/components/store/index'
import jwtToken from '@/components/helpers/jwt'

const wechat_auth = () => import('@/components/wechat/auth/wechat_auth.vue')
const register = () => import('@/components/wechat/auth/register.vue')
const login = () => import('@/components/wechat/auth/login.vue')

const user_index = () => import('@/components/wechat/user/index')
const user_info = () => import('@/components/wechat/user/info')
const edit_info = () => import('@/components/wechat/user/edit_info')
const health = () => import('@/components/wechat/user/health')
const health_detail = () => import('@/components/wechat/user/health_detail')
const concern_index = () => import('@/components/wechat/user/concern/index')

const enterprise_index = () => import('@/components/wechat/enterprise/index')
const admin = () => import('@/components/wechat/enterprise/admin')
const enterprise_info = () => import('@/components/wechat/enterprise/info')
const enterprise_edit_info = () => import('@/components/wechat/enterprise/edit_info')
const enterprise_ref = () => import('@/components/wechat/enterprise/ref')

const worker_index = () => import('@/components/wechat/worker/index')

let routes = [
    {path: '/wechat_auth', name: 'wechat_auth', component: wechat_auth, meta: {wechatAuth: true}},
    {path: '/register', name: 'register', component: register},
    {path: '/login/:username', name: 'login', component: login},
    //个人中心
    {path: '/user_index', name: 'user_index', component: user_index, meta: {wechatAuth: true}},
    {path: '/user_info', name: 'user_info', component: user_info, meta: {wechatAuth: true}},
    {path: '/edit_info', name: 'edit_info', component: edit_info, meta: {wechatAuth: true}},
    {path: '/health/:user_id', name: 'health', component: health, meta: {wechatAuth: true}},
    {path: '/health', name: 'health', component: health, meta: {wechatAuth: true}},
    {path: '/health_detail/:health_id', name: 'health_detail', component: health_detail, meta: {wechatAuth: true}},
    {path: '/concern_index', name: 'concern_index', component: concern_index, meta: {wechatAuth: true}},

    {path: '/enterprise_index', name: 'enterprise_index', component: enterprise_index, meta: {wechatAuth: true}},
    {path: '/admin', name: 'admin', component: admin, meta: {wechatAuth: true}},
    {path: '/enterprise_info', name: 'enterprise_info', component: enterprise_info, meta: {wechatAuth: true}},
    {path: '/enterprise_ref', name: 'enterprise_ref', component: enterprise_ref, meta: {wechatAuth: true}},

    {path: '/enterprise_edit_info', name: 'enterprise_edit_info', component: enterprise_edit_info, meta: {wechatAuth: true}},

    {path: '/worker_index', name: 'worker_index', component: worker_index, meta: {wechatAuth: true}},


];

const router = new VueRouter({
    routes,
    // mode: 'history'
});

router.beforeEach((to, from, next) => {
    //登录用户
    let _this = this
    if (to.path.match('/username/')) {
        let arr = to.path.split('/')
        let username = arr[2]
        Store.dispatch('loginRequest', {username: username, password: username}).then(response => {
            //如果用户没有完善信息，跳转至信息完善页面
            /*axios.post('api/user/info').then(response => {
                if (!response.data.data.mobile) {
                    next('/register')
                } else {
                    next('/user_index')
                }
            })*/
            if (Store.state.AuthUser.mobile) {
                return next('/user_index')
            } else {
                return next('/register')
            }
        })
    }

    if (to.meta.wechatAuth) {
        //验证是否登录
        if (Store.state.AuthUser.authenticated || jwtToken.getToken()) {
            if (Store.state.AuthUser.mobile) {
                return next()
            } else {
                return next('/register')
            }
        } else {
            axios.post('api/wechat/getOauthURL', {path:to.path}).then(response => {
                window.localStorage.setItem('user_to_url', to.path)
                //跳转微信授权页面
                window.location.href = response.data.data
            })
        }
    }

    return next()
});

export default router


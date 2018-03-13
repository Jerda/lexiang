import VueRouter from 'vue-router'
import Store from '@/components/store/index'
import jwtToken from '@/components/helpers/jwt'
//
/**
 * Router懒加载
 */
const login = () => import('@/components/admin/auth/login.vue');
const register = () => import('@/components/admin/auth/register.vue');
const index = () => import('@/components/admin/layouts/index.vue');
// const sms = () => import('@/components/admin/sms/index.vue');

const power = () => import('@/components/admin/system/power.vue');   //权限管理
const admins = () => import('@/components/admin/admins/index.vue');   //管理员管理

const wechat_config = () => import('@/components/admin/wechat/config.vue');
const wechat_menu = () => import('@/components/admin/wechat/menu.vue');
// const wechat_material = () => import('@/components/admin/wechat/material/index.vue');
const wechat_user = () => import('@/components/admin/wechat/user.vue');
const wechat_service = () => import('@/components/admin/wechat/service/index.vue');

const enterprise_index = () => import('@/components/admin/enterprise/index.vue');
const enterprise_add = () => import('@/components/admin/enterprise/add.vue');

const worker_index = () => import('@/components/admin/worker/index.vue');

const user_index = () => import('@/components/admin/user/index.vue');
const user_report = () => import('@/components/admin/user/report.vue');

let routes = [
    {path: '/login', name: 'login', component: login},
    {path: '/register', name: 'register', component: register},
    {path: '/', name: 'index', component: index, children : [
        // {path: '/sms', name: 'sms', component: sms},
        {path: '/power', name: 'power', component: power},
        {path: '/admins', name: 'admins', component: admins},
        {path: '/wechat_config', name: 'wechat_config', component: wechat_config},
        {path: '/wechat_menu', name: 'wechat_menu', component: wechat_menu},
        // {path: '/wechat_material', name: 'wechat_material', component: wechat_material},
        {path: '/wechat_user', name: 'wechat_user', component: wechat_user},
        {path: '/wechat_service', name: 'wechat_service', component: wechat_service},
        {path: '/enterprise_index', name: 'enterprise_index', component: enterprise_index},
        {path: '/enterprise_add/:id', name: 'enterprise_add', component: enterprise_add},
        // {path: '/worker_index', name: 'worker_index', component: worker_index},
        {path: '/worker_index/:enterprise_id', name: 'worker_index', component: worker_index},
        {path: '/user_index', name: 'user_index', component: user_index},
        {path: '/user_report/:user_id', name: 'user_report', component: user_report},
    ], meta: {adminAuth: true}},
];


const router = new VueRouter({
    routes,
});


router.beforeEach((to, from, next) => {
    if (to.meta.adminAuth) {
        if (Store.state.authenticated || jwtToken.getToken()) {
            return next()
        } else {
            return next({'name': 'login'});
        }
    } else {
        return next()
    }
});

export default router

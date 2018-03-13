import Vue from 'vue'
import VueRouter from 'vue-router'
import App from './Wechat.vue'
import axios from 'axios'
import router from './router/index.js'
// import 'element-ui/lib/theme-chalk/index.css'
import store from '@/components/store/index'
import jwtToken from '@/components/helpers/jwt'
import helpers from '@/components/helpers/common'
import jquery from 'jquery'
import jsonp from 'jsonp'
import { ConfirmPlugin, AlertPlugin } from 'vux'

window.axios = axios;
window.$ = jquery;
window.jsonp = jsonp;

Vue.use(VueRouter);
Vue.use(helpers);
Vue.use(ConfirmPlugin);
Vue.use(AlertPlugin);

/*
 将token值加入请求中
 */
axios.interceptors.request.use(function (config) {
    if (jwtToken.getToken()) {
        config.headers['Authorization'] = 'Bearer ' + jwtToken.getToken();
    }
    return config
}, function (error) {
    return Promise.reject(error);
});


axios.interceptors.response.use(function(response) {
    //对响应数据做些事
    return response
},function (error) {
    //请求错误时做些事
    if (error.response.status == 401 || error.response.status == 419) {
        store.dispatch('refreshToken')
        return Promise.reject(error)
    } else {
        return Promise.reject(error)
    }
});


new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: { App }
})

import Vue from 'vue'
import VueRouter from 'vue-router'
import axios from 'axios'
import router from './router/index.js'
import { Message, Loading } from 'element-ui'
// import 'element-ui/lib/theme-chalk/index.css'
import './style/frontawesome.less'
import App from './Admin.vue'
import store from '@/components/store/index'
import jwtToken from '@/components/helpers/jwt'
import helpers from '@/components/helpers/common'
import jquery from 'jquery'

window.axios = axios;
window.$ = jquery;

Vue.use(VueRouter);
Vue.use(Loading.directive);
Vue.prototype.$message = Message;
Vue.prototype.$limit = 10; //分页设置
Vue.use(helpers);

/*
 将token值加入请求中
 */
axios.interceptors.request.use(function (config) {
    if (jwtToken.getToken()) {
        config.headers['Authorization'] = 'Bearer ' + jwtToken.getToken();
    }
    return config;
}, function (error) {
    return Promise.reject(error);
});


axios.interceptors.response.use(function(response) {
    //对响应数据做些事
    return response;
},function (error) {
    //请求错误时做些事
    if (error.response.status == 401 || error.response.status == 419) {
        store.dispatch('refreshToken');
        return Promise.reject(error);
    } else {
        return Promise.reject(error);
    }
});


new Vue({
  el: '#app',
  router,
  store,
  template: '<App/>',
  components: { App }
})

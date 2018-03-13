webpackJsonp([21],{"+BTi":function(e,t){},"2rGO":function(e,t){},"4S7G":function(e,t,n){"use strict";t.a={setToken:function(e){window.localStorage.setItem("jwt_token",e)},getToken:function(){return window.localStorage.getItem("jwt_token")},removeToken:function(){window.localStorage.removeItem("jwt_token")}}},TBJw:function(e,t){},cwe7:function(e,t){},dgJT:function(e,t){},dgq6:function(e,t,n){"use strict";var o=n("7+uW"),r=n("NYxO"),a={state:{username:null,authenticated:!1},mutations:{set_user_auth:function(e,t){e.username=t.user.username,e.authenticated=!0},del_user_auth:function(e){e.username=null,e.authenticated=!1}},actions:{setAuthUser:function(e){var t=e.commit;e.dispatch;return axios.post("/api/user/user").then(function(e){t({type:"set_user_auth",user:e.data})})},delAuthUser:function(e){return(0,e.commit)({type:"del_user_auth"})},refreshToken:function(e){e.commit;var t=e.dispatch;return axios.post("api/auth/refreshToken").then(function(e){t("loginSuccess",e.data)}).catch(function(e){t("logoutRequest")})}}},u=n("//Fk"),i=n.n(u),s=n("4S7G"),c={actions:{loginRequest:function(e,t){var n=e.dispatch;return axios.post("/api/auth/login",t).then(function(e){n("loginSuccess",e.data)}).catch(function(e){return i.a.reject(e)})},loginSuccess:function(e,t){var n=e.dispatch;s.a.setToken(t.token),n("setAuthUser")},logoutRequest:function(e){var t=e.dispatch;return axios.post("/api/user/logout").then(function(e){return s.a.removeToken(),t("delAuthUser"),e})}}};o.default.use(r.a);t.a=new r.a.Store({modules:{AuthUser:a,Login:c}})},hRzL:function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});var o=n("//Fk"),r=n.n(o),a=(n("cwe7"),n("+BTi"),n("2X9z")),u=n.n(a),i=(n("2rGO"),n("nu7/")),s=n.n(i),c=n("7+uW"),d=n("/ocq"),p=n("mtWM"),h=n.n(p),l=n("dgq6"),f=n("4S7G"),m=[{path:"/login",name:"login",component:function(){return n.e(11).then(n.bind(null,"3LtJ"))}},{path:"/register",name:"register",component:function(){return n.e(12).then(n.bind(null,"KOau"))}},{path:"/",name:"index",component:function(){return n.e(5).then(n.bind(null,"iqGI"))},children:[{path:"/power",name:"power",component:function(){return n.e(4).then(n.bind(null,"hmVg"))}},{path:"/admins",name:"admins",component:function(){return n.e(9).then(n.bind(null,"JcE1"))}},{path:"/wechat_config",name:"wechat_config",component:function(){return n.e(10).then(n.bind(null,"6lSk"))}},{path:"/wechat_menu",name:"wechat_menu",component:function(){return n.e(3).then(n.bind(null,"oszc"))}},{path:"/wechat_user",name:"wechat_user",component:function(){return n.e(14).then(n.bind(null,"5s0s"))}},{path:"/wechat_service",name:"wechat_service",component:function(){return n.e(6).then(n.bind(null,"Q9xG"))}},{path:"/enterprise_index",name:"enterprise_index",component:function(){return n.e(1).then(n.bind(null,"kgow"))}},{path:"/enterprise_add/:id",name:"enterprise_add",component:function(){return n.e(7).then(n.bind(null,"kLpG"))}},{path:"/worker_index/:enterprise_id",name:"worker_index",component:function(){return n.e(0).then(n.bind(null,"lrBq"))}},{path:"/user_index",name:"user_index",component:function(){return n.e(2).then(n.bind(null,"2hkW"))}},{path:"/user_report/:user_id",name:"user_report",component:function(){return n.e(17).then(n.bind(null,"sbtC"))}}],meta:{adminAuth:!0}}],g=new d.a({routes:m});g.beforeEach(function(e,t,n){return e.meta.adminAuth?l.a.state.authenticated||f.a.getToken()?n():n({name:"login"}):n()});var _=g,w=(n("TBJw"),{render:function(){var e=this.$createElement,t=this._self._c||e;return t("div",{attrs:{id:"app"}},[t("router-view")],1)},staticRenderFns:[]});var T=n("VU/8")({name:"app"},w,!1,function(e){n("dgJT")},null,null).exports,v=n("odsO"),x=n("7t+N"),k=n.n(x);window.axios=h.a,window.$=k.a,c.default.use(d.a),c.default.use(s.a.directive),c.default.prototype.$message=u.a,c.default.prototype.$limit=10,c.default.use(v.a),h.a.interceptors.request.use(function(e){return f.a.getToken()&&(e.headers.Authorization="Bearer "+f.a.getToken()),e},function(e){return r.a.reject(e)}),h.a.interceptors.response.use(function(e){return e},function(e){return 401==e.response.status||419==e.response.status?(l.a.dispatch("refreshToken"),r.a.reject(e)):r.a.reject(e)}),new c.default({el:"#app",router:_,store:l.a,template:"<App/>",components:{App:T}})},odsO:function(e,t,n){"use strict";var o=n("Wxq9");n.n(o);t.a={install:function(e,t){e.prototype.message=function(e){return void 0===e.data.status?this.$message(e.data.msg):1===e.data.status&&void 0===e.data.data?this.$message.success(e.data.msg):this.$message.error(e.data.msg)},e.prototype.formatSelectOptions=function(e){var t=[];for(var n in e)t.push({value:n,label:e[n]});return t},e.prototype.formatSelectOptionsForVux=function(e){var t=[];for(var n in e)t.push({value:n,name:e[n]});return t},e.prototype.formatToTextForAddress=function(e){var t={};return e.forEach(function(e,n){0==n&&(t.province=o.CodeToText[e]),1==n&&(t.city=o.CodeToText[e]),2==n&&(t.district=o.CodeToText[e])}),t},e.prototype.formatToCodeForAddress=function(e,t,n){var r=[];return r[0]=o.TextToCode[e].code,r[1]=o.TextToCode[e][t].code,r[2]=o.TextToCode[e][t][n].code,r},e.prototype.getPermission=function(e){axios.post("api/frontend_permission",e).then(function(e){return e.data.data})}}}}},["hRzL"]);
//# sourceMappingURL=admin.556387b5d713e561fb19.js.map
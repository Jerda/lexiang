export default {
    state: {
        username: null,
        authenticated: false, //登录状态
        mobile: null
    },
    mutations: {
        set_user_auth(state, payload) {
            console.log(payload)
            state.username = payload.user.data.username
            state.mobile = payload.user.data.mobile
            console.log(state.mobile)
            state.authenticated = true
        },
        del_user_auth(state) {
            state.username = null
            state.authenticated = false
        }
    },
    actions: {
        setAuthUser({commit, dispatch}) {
            axios.post('/api/user/info').then(response => {
                commit({
                    type: 'set_user_auth',
                    user: response.data
                })
                return response
            })
        },
        delAuthUser({commit}) {
            return commit({
                type: 'del_user_auth',
            })
        },
        refreshToken({commit, dispatch}) {
            return axios.post('api/auth/refreshToken').then(response => {
                dispatch('loginSuccess', response.data)
            }).catch(error => {
                dispatch('logoutRequest')
            })
        }
    }
}

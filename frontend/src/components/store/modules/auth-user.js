export default {
    state: {
        username: null,
        authenticated: false, //登录状态
    },
    mutations: {
        set_user_auth(state, payload) {
            state.username = payload.user.data.username
            state.authenticated = true
            console.log('set_user_auth')
        },
        del_user_auth(state) {
            state.username = null
            state.authenticated = false
        }
    },
    actions: {
        setAuthUser({commit, dispatch}) {
            axios.post('/api/user/info').then(response => {
                console.log('info')
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

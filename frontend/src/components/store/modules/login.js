import jwtToken from '@/components/helpers/jwt'

export default {
    actions: {
        loginRequest({dispatch}, formData) {
            return axios.post('/api/auth/login', formData).then(response => {
                dispatch('loginSuccess', response.data)
            }).catch(error => {
                return Promise.reject(error)
            })
        },
        loginSuccess({dispatch}, tokenResponse) {
            jwtToken.setToken(tokenResponse.token)
            dispatch('setAuthUser')
        },
        registerSuccess({dispatch}) {
            dispatch('setAuthUser')
        },
        logoutRequest({dispatch}) {
            return axios.post('/api/user/logout').then(response => {
                jwtToken.removeToken()
                dispatch('delAuthUser')
                return response
            });
        }
    }
}

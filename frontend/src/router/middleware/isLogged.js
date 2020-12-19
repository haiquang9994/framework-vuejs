import store from '@/store'
import Http from '@/libraries/http/http'
import VueCookies from 'vue-cookies'

export default (to, from, next) => {
    let token = VueCookies.get('token')
    if (token === null || token === undefined) {
        next('/login')
    } else {
        if (store.getters.userLoaded) {
            next()
        } else {
            Http.get(process.env.VUE_APP_API_ENDPOINT + 'me')
                .authed(token)
                .sent()
                .then(body => {
                    store.commit('user.apply', body.user_data)
                    store.commit('user.load')
                    store.commit('layout.global.loader.hide')
                    next()
                })
                .catch(() => {
                    VueCookies.remove('token')
                    next('/login')
                })
        }
    }
}
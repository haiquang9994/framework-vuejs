import store from '@/store'
import Http from '@/libraries/http/http'
import VueCookies from 'vue-cookies'

export default (to, from, next) => {
    let token = VueCookies.get('token')
    if (token === null || token === undefined) {
        next('/login')
    } else {
        if (store.state.me_loaded) {
            next()
        } else {
            Http.get(process.env.VUE_APP_API_ENDPOINT + 'me')
                .authed(token)
                .sent()
                .then(body => {
                    store.state.user_data = body.user_data
                    store.state.me_loaded = true
                    next()
                })
                .catch(() => {
                    // token = null
                    store.commit('save')
                    next('/login')
                })
        }
    }
}
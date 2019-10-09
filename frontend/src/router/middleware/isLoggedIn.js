import store from '@/store'
import Http from '@/libraries/http/http'

export default (to, from, next) => {
  if (store.state.token === null || store.state.token === undefined) {
    next('/login')
  } else {
    if (store.state.me_loaded) {
      next()
    } else {
      Http.get(process.env.VUE_APP_API_ENDPOINT + 'me')
      .authed(store.state.token)
      .sent()
      .then(body => {
        store.state.user_data = body.user_data
        store.state.me_loaded = true
        next()
      })
      .catch(() => {
        store.state.token = null
        store.commit('save')
        next('/login')
      })
    }
  }
}
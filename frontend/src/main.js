import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Antd from 'ant-design-vue'
import responsive from 'vue-responsive'
import http from './libraries/http'
import helpers from './libraries/helpers'

import 'ant-design-vue/dist/antd.css'

Vue.config.productionTip = false

Vue.use(Antd)
Vue.use(responsive)
Vue.use(http)
Vue.use(helpers)

// process.env.VUE_APP_API_ENDPOINT + 'me'

// const waitForStorageToBeReady = async (to, from, next) => {
//   await store.restored
//   next()
// }
// router.beforeEach(waitForStorageToBeReady)

router.afterEach(current => {
  store.state.activeMenu.pop()
  store.state.activeMenu.push(current.name)
})

new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Antd from 'ant-design-vue'
import responsive from 'vue-responsive'
import http from './libraries/http'
import helpers from './libraries/helpers'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas, faLongArrowAltUp } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import appendQuery from 'append-query'
import trim from 'trim-character'
import VueMce from 'vue-mce'

import 'ant-design-vue/dist/antd.css'

library.add(fas)
library.add(faLongArrowAltUp)

Vue.config.productionTip = false

Vue.use(Antd)
Vue.use(responsive)
Vue.use(http)
Vue.use(helpers)
Vue.use(VueMce)

Vue.component('fai', FontAwesomeIcon)

const unique_list = [
    'dashboard', 'post_list', 'setting', 'post_update'
]

router.beforeEach((to, from, next) => {
    if (unique_list.indexOf(to.name) > -1) {
        return next()
    }
    for (let k in to.query) {
        if (k.match(/^rf\d{5}$/) !== null) {
            next()
            return
        }
    }
    router.push(trim(appendQuery(to.fullPath, 'rf' + Vue.prototype.$helpers.rNum(5)), '='))
    // next(trim(appendQuery(to.fullPath, 'rf' + Vue.prototype.$helpers.rNum(5)), '='))
})

router.afterEach(current => {
    if (current.meta.active) {
        store.state.activeMenu.pop()
        store.state.activeMenu.push(current.meta.active)
    }
    let use_layout = store.state.layout.use_layout = current.meta.use_layout || false
    if (use_layout) {
        let check = store.state.layout.tabs.filter(tab => {
            return tab.fullPath === current.fullPath
        }).length
        if (check === 0) {
            store.state.layout.tabs.push({
                fullPath: current.fullPath,
                name: current.name,
                path: current.path,
                label: current.meta.label,
            })
        }
        let i = store.state.layout.tab_history.indexOf(current.fullPath)
        if (i > -1) {
            store.state.layout.tab_history.splice(i, 1)
        }
        store.state.layout.tab_history.push(current.fullPath)
        store.state.layout.active_tab = current.fullPath
    }
})

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')

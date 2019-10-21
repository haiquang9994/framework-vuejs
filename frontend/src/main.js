import Vue from 'vue'
import App from './App.vue'
import router from '@/router'
import store from '@/store'
import Antd from 'ant-design-vue'
import responsive from 'vue-responsive'
import http from '@/libraries/http'
import helpers from '@/libraries/helpers'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas, faLongArrowAltUp } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import appendQuery from 'append-query'
import trim from 'trim-character'
import PageTitle from '@/components/PageTitle'
import TinyMce from '@/components/TinyMce'
import FileManager from '@/components/FileManager.vue'
import SelectFile from '@/components/SelectFile'
import UploadFile from '@/components/UploadFile.vue'
import MyCheckbox from '@/components/MyCheckbox.vue'
import Vodal from 'vodal'
import VueCookies from 'vue-cookies'

import 'ant-design-vue/dist/antd.css'

library.add(fas)
library.add(faLongArrowAltUp)

Vue.config.productionTip = false

Vue.use(Antd)
Vue.use(responsive)
Vue.use(http)
Vue.use(helpers)
Vue.use(VueCookies)

Vue.component('fai', FontAwesomeIcon)
Vue.component('page-title', PageTitle)
Vue.component('tiny-mce', TinyMce)
Vue.component(Vodal.name, Vodal)
Vue.component('file-manager', FileManager)
Vue.component('select-file', SelectFile)
Vue.component('upload-file', UploadFile)
Vue.component('my-checkbox', MyCheckbox)

const unique_list = [
    'profile', 'settings', 'post_list', 'post_update'
]

const not_rf_list = [
    'dashboard', 'login',
]

router.beforeEach((to, from, next) => {
    if (not_rf_list.indexOf(to.name) > -1) {
        return next()
    }
    for (let k in to.query) {
        if (k.match(/^rf\d{5}$/) !== null) {
            return next()
        }
    }
    if (unique_list.indexOf(to.name) > -1) {
        let path = store.state.layout.tab_history.find(t => t.startsWith(to.path + '?'))
        if (path) {
            return router.push(path).catch(() => {})
        }
    }
    return router.push(trim(appendQuery(to.fullPath, 'rf' + Vue.prototype.$helpers.rNum(5)), '=')).catch(() => {})
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
        store.commit('save')
        document.title = current.meta.label
    }
})

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')

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
import tinymce from 'vue-tinymce-editor'

import 'ant-design-vue/dist/antd.css'

library.add(fas)
library.add(faLongArrowAltUp)

Vue.config.productionTip = false

Vue.use(Antd)
Vue.use(responsive)
Vue.use(http)
Vue.use(helpers)

Vue.component('fai', FontAwesomeIcon)
Vue.component('tinymce-editor', tinymce)

router.afterEach(current => {
    let use_layout = store.state.layout.use_layout = current.meta.use_layout || false
    if (current.meta.active) {
        store.state.activeMenu.pop()
        store.state.activeMenu.push(current.meta.active)
    }
    if (use_layout) {
        let check = store.state.layout.tabs.filter(tab => {
            return tab.name === current.name
        }).length
        if (check === 0) {
            store.state.layout.tabs.push({
                name: current.name,
                path: current.path,
                label: current.meta.label,
                component: current.meta.name,
            })
        }
        let i = store.state.layout.tab_history.indexOf(current.name)
        if (i > -1) {
            store.state.layout.tab_history.splice(i, 1)
        }
        store.state.layout.tab_history.push(current.name)
        store.state.layout.active_tab = current.name
    }
})

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')

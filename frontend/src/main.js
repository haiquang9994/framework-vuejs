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
Vue.component('vodal', Vodal)
Vue.component('file-manager', FileManager)
Vue.component('select-file', SelectFile)
Vue.component('upload-file', UploadFile)
Vue.component('my-checkbox', MyCheckbox)

new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app')

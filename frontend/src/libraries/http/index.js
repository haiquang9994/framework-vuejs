import Http from './http'

export default {
    install: Vue => {
        Vue.prototype.$http = Http
    }
}

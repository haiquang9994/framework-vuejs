import store from '@/store'

const Helpers = {
    hasRole(role) {
        if (store.state.user_data instanceof Object) {
            let roles = store.state.user_data.roles
            if (roles instanceof Array) {
                if (roles.indexOf('ADMIN') > -1) {
                    return true
                }
                return roles.indexOf(role) > -1
            }
        }
        return false
    }
}

export default {
    install: Vue => {
        Vue.prototype.$helpers = Helpers
        Vue.prototype.$c = function () {
            let args = arguments
            if (args[0] === undefined) {
                throw "Parameter 1 of c method can't be undefined!"
            } else if (args[0] instanceof Object) {
                for (let k in args[0]) {
                    this.$store.state[k] = args[0][k]
                }
                if (args[1]) {
                    this.$store.commit('save')
                }
            } else if (typeof args[0] === 'string') {
                this.$store.state[args[0]] = args[1]
                if (args[2] === true) {
                    this.$store.commit('save')
                }
            }
        }
    }
}

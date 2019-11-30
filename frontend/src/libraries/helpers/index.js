import store from '@/store'
import trim from 'trim-character'
import VueCookies from 'vue-cookies'

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
    },
    makeid(length) {
        let result = '',
            characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
            charactersLength = characters.length
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength))
        }
        return result
    },
    rNum(length) {
        let result = '',
            characters = '0123456789',
            charactersLength = characters.length
        for (let i = 0; i < length; i++) {
            result += characters.charAt(Math.floor(Math.random() * charactersLength))
        }
        return result
    },
    getToken() {
        return VueCookies.get('token')
    }
}

export default {
    install: Vue => {
        Vue.prototype.$helpers = Helpers
        Vue.prototype.$go = function (path) {
            this.$router.push(path).catch(() => {})
        }
        Vue.prototype.$log = function (data) {
            console.log(data)
        }
        Vue.prototype.$validate = function (content, value) {
            if (content.handle(value)) {
                content.status = ''
                content.help = ''
            } else {
                content.status = 'error'
                content.help = content.message
            }
        }
        Vue.prototype.$validate_all = function (validator, data) {
            let status = true
            for (let key in validator) {
                let content = validator[key]
                if (content.handle(data[content.key] || data[key])) {
                    content.status = ''
                    content.help = ''
                } else {
                    status = false
                    content.status = 'error'
                    content.help = content.message
                }
            }
            return status
        }
        Vue.prototype.$replaceActiveTab = function (path, find_old_tab) {
            this.$store.state.layout.tab_history.pop()
            this.$store.state.layout.tabs.pop()
            if (path === undefined) {
                return
            }
            if (find_old_tab === true) {
                let tab = this.$store.state.layout.tabs.find(t => t.path === path)
                if (tab !== undefined) {
                    this.$router.push(tab.fullPath)
                    return
                }
            }
            this.$router.push(path)
        }
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
        Vue.prototype.$token = function () {
            return this.$cookies.get('token')
        }
        Vue.prototype.$filemanagerOpen = function (target) {
            this.$store.state.file_manager.target = target
            this.$store.state.file_manager.show = true
        }
        Vue.prototype.$filemanagerClose = function () {
            this.$store.state.file_manager.show = false
        }
        Vue.prototype.$mergeUrl = function () {
            let _ = []
            for (let i = 0; i < arguments.length; i++) {
                if (typeof arguments[i] === 'string') {
                    _.push(trim(arguments[i], '/'))
                }
            }
            let url = _.filter(i => i !== '').join('/')
            return url.startsWith('http') ? url : ('/' + url)
        }
    }
}

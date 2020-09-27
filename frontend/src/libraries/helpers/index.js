import store from '@/store'
import trim from 'trim-character'
import VueCookies from 'vue-cookies'
import queryString from 'query-string'

const helpers = {
    hasRole(role) {
        if (store.getters.user instanceof Object) {
            let roles = store.getters.user.roles
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
    },
    objectSort(obj) {
        let ordered = {}
        Object.keys(obj).sort().forEach(function(key) {
            ordered[key] = obj[key]
        })
        return ordered
    },
    removeRfParam(obj) {
        let result = {}
        for (let k in obj) {
            if (k.match(/^rf\d{5}$/) === null) {
                result[k] = obj[k]
            }
        }
        return result
    },
    getQueryParams(url) {
        let i = url.indexOf('?')
        if (i > -1) {
            return queryString.parse(url.substring(i))
        }
        return {}
    },
}

export {
    helpers
}

const images = require.context('@/assets/', false, /\.png$|\.jpg$/)

export default {
    install: Vue => {
        Vue.prototype.$helpers = helpers
        Vue.prototype.$assets = function (url) {
            return images('./' + url)
        }
        Vue.prototype.$go = function (path, replace = false) {
            if (replace === true) {
                this.$router.replace(path).catch(() => {})
            } else {
                this.$router.push(path).catch(() => {})
            }
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
        Vue.prototype.$token = function () {
            return this.$cookies.get('token')
        }
        Vue.prototype.$filemanagerOpen = function (target) {
            this.$store.state.fileManager.target = target
            this.$store.state.fileManager.enable = true
        }
        Vue.prototype.$filemanagerClose = function () {
            this.$store.state.fileManager.enable = false
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

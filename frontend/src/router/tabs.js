import { helpers } from '../libraries/helpers'
import store from '@/store'

const unique_list = {
    lv1: [
        'profile', 'settings', 'login',
    ],
    lv2: [
        'post_list', 'post_update',
        'post_category_list', 'post_category_update',
    ]
}

function canAddTab(data) {
    if (data.name == 'dashboard') {
        return false
    }
    if (unique_list.lv1.indexOf(data.name) > -1) {
        return store.state.layout.tabs.filter(tab => tab.name === data.name).length === 0
    }
    return store.state.layout.tabs.filter(tab => tab.fullPath === data.fullPath).length === 0
}

class TabUrl {
    constructor(fullPath) {
        this.queries = {}
        this.q = ''
        this.rf = null
        this.path = undefined
        this.fullPath = undefined
        this.parse(fullPath)
    }
    parse(fullPath) {
        let queries = {}
        let vars = fullPath.split('?'),
            q = vars[1] ?? ''
        this.path = vars[0]
        q.split('&').sort().map(o => o.split('=')).forEach(item => {
            if (item[0]) {
                if (item[0].match(/^rf\d+/)) {
                    this.rf = item[0]
                } else {
                    queries[item[0]] = item[1] || ''
                }
            }
        })
        this.queries = queries
        let q_array = []
        for (let k in queries) {
            q_array.push(k + '=' + queries[k] || '')
        }
        this.fullPath = q_array.length > 0 ? this.path + '?' + q_array.join('&') : this.path
        this.q = q_array.join('&')
        if (this.rf) {
            q_array.push(this.rf)
        }
        this.format = q_array.length > 0 ? this.path + '?' + q_array.join('&') : this.path
    }
    hasRf() {
        return this.rf !== null
    }
    addRf() {
        if (this.rf === null) {
            this.rf = 'rf' + helpers.rNum(5)
            let q_array = []
            for (let k in this.queries) {
                q_array.push(k + '=' + this.queries[k] || '')
            }
            q_array.push(this.rf)
            this.format = this.path + '?' + q_array.join('&')
        }
    }
}

function routerBeforeEach(router, to, from, next) {
    if (to.name === 'dashboard' || unique_list.lv1.indexOf(to.name) > -1) {
        return next()
    }
    let tabUrl = new TabUrl(to.fullPath)
    if (unique_list.lv2.indexOf(to.name) > -1) {
        // Redirect nếu link ban đâu khác link được sort query
        if (tabUrl.format !== to.fullPath) {
            router.push(tabUrl.format).catch(() => { })
            return
        }
        // Lv2 không có rf
        if (!tabUrl.hasRf()) {
            let tab = store.state.layout.tabs.filter(tab => {
                return tab.path === to.path && (new TabUrl(tab.fullPath)).q === tabUrl.q
            })[0]
            if (tab) {
                if (tab.fullPath !== tabUrl.format) {
                    router.push(tab.fullPath).catch(() => { })
                    return
                }
            } else {
                tabUrl.addRf()
                router.push(tabUrl.format).catch(() => { })
                return
            }
        }
        return next()
    }
    if (!tabUrl.hasRf()) {
        tabUrl.addRf()
        router.push(tabUrl.format).catch(() => { })
        return
    }
    return next()
}

function routerAfterEach(current) {
    document.title = 'Admin | ' + current.meta.label
    store.state.layout.use_layout = current.meta.use_layout || false
    if (current.meta.active) {
        store.state.activeMenu = [current.meta.active]
    }
    if (store.state.layout.use_layout) {
        if (canAddTab(current)) {
            store.state.layout.tabs.push({
                fullPath: current.fullPath,
                name: current.name,
                path: current.path,
                label: current.meta.label,
            })
        }
    }
    store.state.layout.active_tab = current.fullPath
    if (store.state.layout.tab_history.indexOf(current.fullPath) > -1) {
        store.state.layout.tab_history = store.state.layout.tab_history.filter(o => o != current.fullPath)
    }
    store.state.layout.tab_history.push(current.fullPath)
    store.commit('save')
}

export {
    routerBeforeEach,
    routerAfterEach
}

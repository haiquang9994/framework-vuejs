import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'
// import VueCookies from 'vue-cookies'

Vue.use(Vuex)

// function isRemember() {
//     return VueCookies.get('remember-me') === '1'
// }

export default new Vuex.Store({
    state: {
        __: false,
        file_manager: {
            show: false,
            target: null
        },
        user_data: {},
        me_loaded: false,
        token: null,
        layout: {
            use_layout: false,
            tabs: [
                { name: 'dashboard', label: 'Dashboard', path: '/', fullPath: '/', component: 'Dashboard' }
            ],
            tab_history: ['/']
        },
        tmp: {},
        activeMenu: [],
    },
    getters: {
    },
    mutations: {
        save(state) {
            state.__ = !state.__
        }
    },
    actions: {
    },
    plugins: [createPersistedState({
        storage: window.localStorage,
        // setState(key, value) {
        //     if (isRemember()) {
        //         window.localStorage.setItem(key, JSON.stringify(value))
        //     } else {
        //         VueCookies.set(key, value, 0)
        //     }
        // },
        // getState(key) {
        //     let data = null
        //     try {
        //         if (isRemember()) {
        //             data = JSON.parse(window.localStorage.getItem(key))
        //         } else {
        //             data = VueCookies.get(key)
        //         }
        //     } catch (e) {
        //         data = null
        //     }
        //     return data
        // },
        key: '__fw__',
        paths: ['__', 'token', 'layout']
    })]
})

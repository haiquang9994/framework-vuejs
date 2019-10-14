import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        __: false,
        user_data: {},
        me_loaded: false,
        token: null,
        layout: {
            use_layout: false,
            tabs: [
                { name: 'dashboard', label: 'Dashboard', path: '/', component: 'Dashboard' }
            ],
            tab_history: ['dashboard']
        },
        activeMenu: [],
        __finder_dir: '.',
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
        key: '__fw__',
        paths: ['__', 'token', '__finder_dir']
    })]
})

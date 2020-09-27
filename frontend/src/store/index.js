import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

const layout = {
    state: {
        enable: true,
        sidebarCollapsed: false,
        sidebarSelected: [],
        globalLoaderState: true,
    },
    mutations: {
        'layout.status'(state, status) {
            state.enable = status
        },
        'layout.sidebar.toggle'(state) {
            state.sidebarCollapsed = !state.sidebarCollapsed
        },
        'layout.sidebar.select'(state, name) {
            state.sidebarSelected = [name]
        },
        'layout.global.loader.hide'(state) {
            state.globalLoaderState = false
        },
    },
    getters: {
        sidebarCollapsed(state) {
            return state.sidebarCollapsed
        },
        sidebarSelected(state) {
            return state.sidebarSelected
        },
        globalLoaderState(state) {
            return state.globalLoaderState
        },
    },
}

const user = {
    state: {
        data: {},
        loaded: false,
    },
    mutations: {
        'user.apply'(state, data) {
            state.data = data
        },
        'user.load'(state) {
            state.loaded = true
        },
        'user.unload'(state) {
            state.loaded = false
        },
    },
    getters: {
        user: (state) => state.data,
        userLoaded: (state) => state.loaded,
    },
}

const fileManager = {
    state: {
        enable: false,
        target: null,
    },
}

export default new Vuex.Store({
    modules: {
        layout,
        user,
        fileManager,
    },
    plugins: [createPersistedState({
        storage: window.localStorage,
        key: '__vfn__',
        paths: ['layout.sidebarCollapsed']
    })],
})

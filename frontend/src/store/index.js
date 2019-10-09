import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    __: false,
    activeMenu: [],
    user_data: {},
    me_loaded: false
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
    paths: ['__', 'token']
  })]
})

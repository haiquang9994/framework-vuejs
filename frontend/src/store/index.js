import Vue from 'vue'
import Vuex from 'vuex'
import createPersistedState from 'vuex-persistedstate'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    activeMenu: [],
    token: null,
  },
  getters: {
    name (state) {
      return state.name
    }
  },
  mutations: {
    setToken(state, params) {
      state.token = params.token
    },
    setName(state, params) {
      state.name = params.name      
    }
  },
  actions: {

  },
  plugins: [createPersistedState({
    storage: window.localStorage,
    key: '__fw__'
  })]
})

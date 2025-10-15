import { createStore } from 'vuex'

export default createStore({
  state: {
    visitaId: null,
    online: navigator.onLine
  },
  mutations: {
    setVisitaId(state, id) {
      state.visitaId = id
    },
    setOnlineStatus(state, status) {
      state.online = status
    }
  },
  actions: {
    checkConnection({ commit }) {
      commit('setOnlineStatus', navigator.onLine)
    }
  }
})

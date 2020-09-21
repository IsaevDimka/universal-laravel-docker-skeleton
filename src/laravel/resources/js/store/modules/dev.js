import Cookies from 'js-cookie'
const state = {
  drawer: Cookies.get('devmode') === 'true' || false
}

const actions = {
  devModeSwitch({ commit }, payload) {
    Cookies.set('devmode', payload)
    commit('DEV_MODE_SWITCH', payload)
  }
}

const mutations = {
  DEV_MODE_SWITCH: (state, payload) => {
    state.drawer = payload
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

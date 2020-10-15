import Vue from 'vue'
import Echo from 'laravel-echo'
window.io = require('socket.io-client')

const state = {
  socketStatus: false
}
const mutations = {
  SET_SOCKET_STATUS: (state, payload) => {
    state.socketStatus = payload
  }
}
const actions = {
  connect({ commit, rootState }) {
    const token = rootState.user.token;

    const options = {
      broadcaster: 'socket.io',
      host: 'http://'+ window.location.hostname + ':6001',
      auth: {
        headers: {
          Authorization: `Bearer ${token}`
        }
      }
    }
    return new Promise(resolve => {
      const echo = new Echo(options)
      Vue.prototype.$echo = echo
      echo.connector.socket.on('connect', () => {
        commit('SET_SOCKET_STATUS', true)
        resolve()
      })
      echo.connector.socket.on('disconnect', () => {
        commit('SET_SOCKET_STATUS', false)
      })
    })
  },
  sendMessage(ctx) {
    console.log('sendMessage', ctx)
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}

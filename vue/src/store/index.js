import { createStore } from "vuex";
import axiosClient from '../axios'

const store = createStore({
    state: {
        user: {
            data: {},
            token: sessionStorage.getItem("TOKEN"),
        },
    },
    getters: {},
    actions: {
      register({commit}, user) {
        return axiosClient.post('/register', user)
          .then(({data}) => {
            commit('setUser', data.user);
            commit('setToken', data.token)
            return data;
          })
      },

      login({commit}, user) {
        return axiosClient.post('/login', user)
          .then(({data}) => {
            commit('setUser', data.user);
            commit('setToken', data.token)
            return data;
          })
      },

      logout({commit}) {
        return axiosClient.post('/logout')
          .then(response => {
            commit('logout')
            return response;
          })
      },
    },
    mutations: {
      logout: (state) => {
        state.user.token = null;
        state.user.data = {};
      },

      setUser: (state, user) => {
        state.user.data = user;
      },

      setToken: (state, token) => {
        state.user.token = token;
        sessionStorage.setItem('TOKEN', token);
      },
    },
    modules: {},
});

export default store;

import { createStore } from "vuex";

const store = createStore({
    state: {
        user: {
            data: {},
            token: null,
        },
    },
    getters: {},
    actions: {
      logout({commit}) {
        commit('logout')
      },
    },
    mutations: {
      logout: (state) => {
        state.user.token = null;
        state.user.data = {};
      },
    },
    modules: {},
});

export default store;

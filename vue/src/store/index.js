import { createStore } from "vuex";
import axiosClient from '../axios'

const store = createStore({
    state: {
      user: {
          data: {},
          token: sessionStorage.getItem("TOKEN"),
      },

      posts: {
        loading: false,
        links: [],
        data: []
      },

      currentPost: {
        data: {},
        loading: false,
      },

      notification: {
        show: false,
        type: 'success',
        message: ''
      }
    },

    getters: {
    },
    
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

      getPosts({ commit }, searchQuery, {url = null} = {}) {
        commit('setPostsLoading', true);
        url = url || "/posts";
        return axiosClient.get(url, {
          params: {
          q: searchQuery,
        },
        }).then((res) => {
          commit('setPostsLoading', false);
          commit("setPosts", res.data);
          return res;
        });
      },

      getUser({commit}) {
        return axiosClient.get('/user')
          .then(res => {
            commit('setUser', res.data)
          })
      },
      deletePost({ dispatch }, id) {
        return axiosClient.delete(`/posts/${id}`).then((res) => {
          dispatch('getPosts')
          return res;
        });
      },

      getPost({ commit }, id) {
        commit("setCurrentPostLoading", true);
        return axiosClient
          .get(`/posts/${id}`)
          .then((res) => {
            commit("setCurrentPost", res.data);
            commit("setCurrentPostLoading", false);
            return res;
          })
          .catch((err) => {
            commit("setCurrentPostLoading", false);
            throw err;
          });
      },

      savePost({ commit, dispatch }, post) {
        let response;
        if (post.id) {
          response = axiosClient
            .put(`/posts/${post.id}`, post)
            .then((res) => {
              commit('setCurrentPost', res.data)
              return res;
            });
        } else {
          response = axiosClient.post("/posts", post).then((res) => {
            commit('setCurrentPost', res.data)
            return res;
          });
        }

        return response;
      }
    },

    mutations: {
      logout: (state) => {
        state.user.token = null;
        state.user.data = {};
        sessionStorage.removeItem("TOKEN");
      },

      setUser: (state, user) => {
        state.user.data = user;
      },

      setToken: (state, token) => {
        state.user.token = token;
        sessionStorage.setItem('TOKEN', token);
      },

      setPostsLoading: (state, loading) => {
        state.posts.loading = loading;
      },

      setPosts: (state, posts) => {
        state.posts.links = posts.meta.links;
        state.posts.data = posts.data;
      },

      setCurrentPostLoading: (state, loading) => {
        state.currentPost.loading = loading;
      },

      setCurrentPost: (state, post) => {
        state.currentPost.data = post.data;
      },
      notify: (state, {message, type}) => {
        state.notification.show = true;
        state.notification.type = type;
        state.notification.message = message;
        setTimeout(() => {
          state.notification.show = false;
        }, 3000)
      },
    },
    modules: {},
});

export default store;

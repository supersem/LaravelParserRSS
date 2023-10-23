<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">Posts</h1>
        <div>
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Search posts..."
            class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300"
          />
        </div>
        <router-link
          :to="{ name: 'PostCreate' }"
          class="py-2 px-3 text-white bg-emerald-500 rounded-md hover:bg-emerald-600"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 -mt-1 inline-block"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 4v16m8-8H4"
            />
          </svg>
          Add new Post
        </router-link>
      </div>
    </template>
    <div v-if="posts.loading" class="flex justify-center">Loading...</div>
    <div v-else-if="posts.data.length">
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-1 md:grid-cols-1">
      <div
        v-for="post in posts.data"
        :key="post.id"
        class="flex flex-col py-4 px-6 shadow-md bg-white hover:bg-gray-50 h-[270px]"
      >
        <h4 class="mt-4 md-5 text-lg font-bold">{{ post.title  }}</h4>
        <div v-html="post.content" class="py-4 overflow-hidden flex-1"></div>
        <div v-html="post.published_at" class="py-4 overflow-hidden flex-1"></div>
        <div class="flex justify-between items-center mt-3">
          <router-link
            :to="{ name: 'PostView', params: { id: post.id } }"
            class="
              flex
              py-2
              px-4
              border
              border-transparent
              text-sm
              rounded-md
              text-white
              bg-indigo-600
              hover:bg-indigo-700
              focus:ring-2
              focus:ring-offset-2
              focus:ring-indigo-500
            "
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path
                d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
            </svg>
            Edit
          </router-link>
          <button
            v-if="post.id"
            type="button"
            @click="deletePost(post)"
            class="h-8 w-8 flex items-center justify-center rounded-full border border-transparent text-sm text-red-500 focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
              <path
                fill-rule="evenodd"
                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                clip-rule="evenodd" />
            </svg>
          </button>
      </div>
      </div>
    </div>
      <div class="flex justify-center mt-5">
        <nav
          class="relative z-0 inline-flex justify-center rounded-md shadow-sm -space-x-px"
          aria-label="Pagination"
        >
          <a
            v-for="(link, i) of posts.links"
            :key="i"
            :disabled="!link.url"
            href="#"
            @click="getForPage($event, link)"
            aria-current="page"
            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium whitespace-nowrap"
            :class="[
              link.active
                ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
              i === 0 ? 'rounded-l-md bg-gray-100 text-gray-700' : '',
              i === posts.links.length - 1 ? 'rounded-r-md' : '',
            ]"
            v-html="link.label"
          >
          </a>
        </nav>
      </div>
    </div>
    <div v-else class="text-gray-600 text-center py-16">
      Your don't have posts yet
    </div>
  </PageComponent>
</template>


<script setup>
import store from "../store";
import {ref, computed, watchEffect} from "vue";
import PageComponent from '../components/PageComponent.vue'

const posts = computed(() => store.state.posts);

const searchQuery = ref(null);

const getPosts = () => {
      store.dispatch('getPosts', searchQuery.value);
    };

watchEffect((searchQuery) => {
  getPosts();
});

function deletePost(post) {
  if (
    confirm(
      `Are you sure you want to delete this post?`
    )
  ) {
    store.dispatch("deletePost", post.id).then(() => {
      store.dispatch("getPosts");
    });
  }
}

function getForPage(ev, link) {
  ev.preventDefault();
  if (!link.url || link.active) {
    return;
  }

  store.dispatch("getPosts", { url: link.url });
}
</script>

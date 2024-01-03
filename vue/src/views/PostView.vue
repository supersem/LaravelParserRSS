<template>
  <PageComponent>
    <template v-slot:header>
      <div class="flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-900">
          {{ route.params.id ? model.title : "Create a Post" }}
        </h1>
        <button
          v-if="route.params.id"
          type="button"
          @click="deletePost()"
          class="py-2 px-3 text-white bg-red-500 rounded-md hover:bg-red-600 post-view-del-button"
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 -mt-1 inline-block"
            viewBox="0 0 20 20"
            fill="currentColor"
          >
            <path
              fill-rule="evenodd"
              d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
              clip-rule="evenodd"
            />
          </svg>
          Delete Post
        </button>
      </div>
    </template>
    <div v-if="postLoading" class="flex justify-center">Loading...</div>
    <form v-else @submit.prevent="savePost">
      <div class="shadow sm:rounded-md sm:overflow-hidden">
        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
          <div>
            <label for="title" class="block text-sm font-medium text-gray-700"
            >Title</label
            >
            <input
              type="text"
              name="title"
              id="title"
              v-model="model.title"
              autocomplete="post_title"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label for="content" class="block text-sm font-medium text-gray-700"
            >Content</label
            >
            <input
              type="text"
              name="content"
              id="content"
              v-model="model.content"
              autocomplete="post_content"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label for="author" class="block text-sm font-medium text-gray-700"
            >Author</label
            >
            <input
              type="text"
              name="author"
              id="author"
              v-model="model.author"
              autocomplete="post_author"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label for="url" class="block text-sm font-medium text-gray-700"
            >Url</label
            >
            <input
              type="text"
              name="url"
              id="url"
              v-model="model.url"
              autocomplete="post_url"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label for="category" class="block text-sm font-medium text-gray-700"
            >Category</label
            >
            <input
              type="text"
              name="category"
              id="category"
              v-model="model.category"
              autocomplete="post_category"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
          <div>
            <label for="published_at" class="block text-sm font-medium text-gray-700"
            >Published</label
            >
            <input
              type="text"
              name="published_at"
              id="published_at"
              v-model="model.published_at"
              autocomplete="post_published_at"
              class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
            />
          </div>
        </div>
        <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
          <button
            type="button"
            @click="hideModal"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="ml-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            Save
          </button>
        </div>
      </div>
    </form>

  </PageComponent>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import store from "../store";
import PageComponent from '../components/PageComponent.vue'

const router = useRouter();
const route = useRoute();

const postLoading = computed(() => store.state.currentPost.loading);

let model = ref({
  title: "",
  content: "",
  author: "",
  url: "",
  category: "",
});

watch(
  () => store.state.currentPost.data,
  (newVal, oldVal) => {
    model.value = {
      ...JSON.parse(JSON.stringify(newVal)),
      status: !!newVal.status,
    };
  }
);

if (route.params.id) {
  store.dispatch("getPost", route.params.id);
}

function savePost() {
  let action = "created";
  if (model.value.id) {
    action = "updated";
  }
  store.dispatch("savePost", { ...model.value }).then(({ data }) => {
    store.commit("notify", {
      type: "success",
      message: "The post was successfully " + action,
    });
    router.push({
      name: "Posts",
    });
  });
}

function deletePost() {
  if (
    confirm(
      `Are you sure you want to delete this post? Operation can't be undone!!`
    )
  ) {
    store.dispatch("deletePost", model.value.id).then(() => {
      router.push({
        name: "Posts",
      });
    });
  }
}

function hideModal() {
  router.push({
    name: "Posts",
  });
}
</script>

<style scoped>
  .post-view-del-button{
    min-width: fit-content;
    margin-left: 30px;
  }
</style>

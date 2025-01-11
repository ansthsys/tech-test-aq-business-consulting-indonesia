<script setup>
import { computed } from "vue";
import { TrashIcon } from "@heroicons/vue/24/solid";

const { item } = defineProps([
  "item",
  "isLoading",
  "handlerStatus",
  "handlerEditable",
  "handlerDelete",
]);

const classCard = computed(() => {
  return {
    "bg-base-100": item.completed == false,
    "bg-zinc-300": item.completed == true,
  };
});

const classTitle = computed(() => {
  return {
    "line-through": item.completed == true,
    "text-decoration-none": item.completed == false,
  };
});
</script>

<template>
  <div
    class="card card-compact flex-row items-center shadow-md mb-5"
    :class="classCard"
  >
    <div class="card-body flex-none">
      <input
        type="checkbox"
        class="checkbox checkbox-lg border-2"
        @click="handlerStatus(item.id)"
        :checked="item.completed"
      />
    </div>
    <div class="card-body flex-1 cursor-pointer" title="Edit ToDo! ini">
      <h1
        class="card-title break-words break-all line-clamp-4"
        :class="classTitle"
        @click="handlerEditable(item)"
        :contenteditable="item.editable"
        @input="({ target }) => (item.title = target.innerText)"
        autocorrect="off"
        spellcheck="false"
      >
        {{ item.title }}
      </h1>
    </div>
    <div class="card-body flex-none">
      <button
        class="btn btn-error"
        @click.prevent="handlerDelete(item.id)"
        :disabled="isLoading"
      >
        <span v-if="isLoading" class="loading loading-spinner"></span>
        <TrashIcon class="size-5" v-else />
      </button>
    </div>
  </div>
</template>

<style scoped></style>

<script setup>
import { computed, ref } from "vue";
import { TrashIcon } from "@heroicons/vue/24/solid";
import contenteditable from "vue-contenteditable";

const props = defineProps([
  "item",
  "isLoading",
  "handlerStatus",
  "handlerEditable",
  "handlerDelete",
]);

const elementTitle = ref(null);

const classCard = computed(() => {
  return {
    "bg-base-100": props.item.completed == false,
    "bg-zinc-300": props.item.completed == true,
  };
});

const classTitle = computed(() => {
  return {
    "line-through": props.item.completed == true,
    "text-decoration-none": props.item.completed == false,
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
        @click="handlerStatus(props.item.id)"
        :checked="props.item.completed"
      />
    </div>
    <div class="card-body flex-1">
      <contenteditable
        ref="elementTitle"
        tag="h1"
        title="Edit ToDo! ini"
        class="card-title text-2xl break-words break-all line-clamp-4"
        :class="classTitle"
        :contenteditable="true"
        v-model="props.item.title"
        :no-nl="true"
        :no-html="true"
        @returned="handlerEditable(props.item?.id)"
        @blur="handlerEditable(props.item?.id)"
        spellcheck="false"
        translate="no"
      />
    </div>
    <div class="card-body flex-none">
      <button
        class="btn btn-error"
        @click.prevent="handlerDelete(props.item.id)"
        :disabled="isLoading"
      >
        <span v-if="isLoading" class="loading loading-spinner"></span>
        <TrashIcon class="size-5" v-else />
      </button>
    </div>
  </div>
</template>

<style scoped></style>

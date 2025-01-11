<script setup>
const { isLoading, text } = defineProps(["isLoading", "text"]);
</script>

<template>
  <p
    v-if="isLoading"
    class="inline-flex flex-row gap-3 items-center justify-center"
  >
    <span class="loading loading-spinner"></span>
    Loading
  </p>
  <template v-else>
    {{ text }}
  </template>
</template>

<style scoped></style>

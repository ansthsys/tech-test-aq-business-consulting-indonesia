<script setup>
import { RouterLink } from "vue-router";
</script>

<template>
  <div class="min-h-screen w-full flex flex-col items-center justify-center">
    <h1 class="text-6xl font-black text-center mb-5">Page Not Found</h1>
    <RouterLink :to="{ name: 'home' }" class="btn btn-outline mx-auto">
      Back to Home
    </RouterLink>
  </div>
</template>

<style scoped></style>

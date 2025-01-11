<script setup>
import { onMounted } from "vue";
import { RouterView, useRouter } from "vue-router";
import Navbar from "@/components/Navbar.vue";
import { useAuthStore } from "./stores/auth";
import { storeToRefs } from "pinia";

const router = useRouter();
const { isAuthenticated } = storeToRefs(useAuthStore());
const { verifyToken } = useAuthStore();

onMounted(() => {
  verifyToken();

  console.log(isAuthenticated.value);

  router.beforeEach((to, from, next) => {
    console.log(to.meta.requiresAuth, to.meta.requiresGues)
    console.log(to.meta.requiresAuth && !isAuthenticated.value)
    if (to.meta.requireAuth && !isAuthenticated.value) {
      next({ name: "login" });
    } else {
      next();
    }
  });

  router.beforeEach((to, from, next) => {
    if (to.meta.requireGuest && isAuthenticated.value) {
      return next(from);
    } else {
      return next();
    }
  });
});
</script>

<template>
  <div class="min-h-screen w-full">
    <Navbar />

    <RouterView></RouterView>
  </div>
</template>

<style scoped></style>

<script setup>
import { onMounted } from "vue";
import { storeToRefs } from "pinia";
import { useAuthStore } from "@/stores/auth";
import Logo from "@/assets/logo.svg";

const store = useAuthStore();
const { isAuthenticated, dataToken } = storeToRefs(store);
const { verifyToken, revokeToken } = store;

onMounted(() => {
  verifyToken();
});
</script>

<template>
  <nav class="bg-primary/20 sticky top-0 z-10">
    <div class="container mx-auto navbar backdrop-blur-sm text-primary-content">
      <div class="flex-1">
        <RouterLink
          :to="{ name: 'home' }"
          class="btn btn-ghost text-xl font-bold flex flex-col items-center justify-center gap-3"
        >
          <img :src="Logo" alt="Logo" class="size-8" />
          ToDo!
        </RouterLink>
      </div>
      <div class="flex-none">
        <template v-if="!isAuthenticated">
          <div class="inline-flex gap-3">
            <RouterLink
              :to="{ name: 'register' }"
              class="btn btn-primary btn-outline"
            >
              Register
            </RouterLink>
            <RouterLink :to="{ name: 'login' }" class="btn btn-primary">
              Login
            </RouterLink>
          </div>
        </template>
        <template v-else>
          <div class="dropdown dropdown-end">
            <div
              tabindex="0"
              role="button"
              class="btn btn-ghost btn-circle avatar placeholder"
            >
              <div class="w-10 rounded-full bg-primary">
                <span>{{ dataToken.name }}</span>
              </div>
            </div>
            <ul
              tabindex="0"
              class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow gap-1"
            >
              <li>
                <a class="btn btn-sm btn-ghost">Halo {{ dataToken.name }}~</a>
              </li>
              <li>
                <a class="btn btn-sm btn-ghost">{{ dataToken.email }}</a>
              </li>
              <li>
                <a
                  @click.prevent="revokeToken"
                  class="btn btn-sm btn-outline btn-error"
                >
                  Logout
                </a>
              </li>
            </ul>
          </div>
        </template>
      </div>
    </div>
  </nav>
</template>

<style scoped></style>

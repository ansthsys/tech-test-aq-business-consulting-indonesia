<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import axios from "@/config/axios";
import { useAuthStore } from "@/stores/auth";
import TextInput from "@/components/TextInput.vue";
import ButtonLoader from "@/components/ButtonLoader.vue";

const router = useRouter();
const { verifyToken } = useAuthStore();

const response = reactive({
  isLoading: false,
  error: false,
  data: null,
});

const form = reactive({
  email: "",
  password: "",
});

async function handlerSubmit(e) {
  try {
    response.isLoading = true;

    const { data, error } = await axios.post("/login", JSON.stringify(form));

    if (!error) {
      localStorage.setItem("token", data.data.access_token);
      verifyToken();
      router.push({ name: "home" });
    }
  } catch (err) {
    const { errors } = err.response?.data;

    response.error = true;
    response.errors = errors;
  } finally {
    response.isLoading = false;
  }
}
</script>

<template>
  <div class="container mx-auto w-full py-20">
    <form class="flex-none" @submit.prevent="handlerSubmit">
      <div class="card bg-base-200 w-full max-w-sm mx-auto">
        <div class="card-body">
          <h1 class="card-title mb-3">Login ToDo!</h1>

          <TextInput
            label="Email"
            placeholder="Ketik alamat email"
            name="email"
            type="email"
            :errors="response.errors"
            :reactive-state="form"
          />

          <TextInput
            label="Password"
            placeholder="Masukan password"
            name="password"
            type="password"
            :errors="response.errors"
            :reactive-state="form"
          />

          <div class="card-action mt-8">
            <button
              type="submit"
              class="btn btn-primary w-full"
              :disabled="response.isLoading"
            >
              <ButtonLoader :is-loading="response.isLoading" text="Login" />
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped></style>

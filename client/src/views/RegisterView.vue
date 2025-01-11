<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import axios from "@/config/axios";
import TextInput from "@/components/TextInput.vue";
import ButtonLoader from "@/components/ButtonLoader.vue";

const router = useRouter();

const response = reactive({
  isLoading: false,
  error: false,
  data: null,
});

const form = reactive({
  name: "",
  email: "",
  password: "",
  passwordConfirmation: "",
});

async function handlerSubmit(e) {
  try {
    response.isLoading = true;

    const { error } = await axios.post("/register", JSON.stringify(form));

    if (!error) return router.push({ name: "login" });
  } catch (err) {
    const { errors } = err.response.data;

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
          <h1 class="card-title mb-3">Registrasi Akun</h1>

          <TextInput
            label="Nama"
            placeholder="Masukan nama"
            name="name"
            type="text"
            :errors="response.errors"
            :reactive-state="form"
          />

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

          <TextInput
            label="Konfirmasi Password"
            placeholder="Masukan konfirmasi password"
            name="password_confirmation"
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
              <ButtonLoader
                :is-loading="response.isLoading"
                text="Registrasi"
              />
            </button>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<style scoped></style>

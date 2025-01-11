<script setup>
import { onMounted, ref, reactive } from "vue";
import { PlusIcon } from "@heroicons/vue/24/solid";
import { VueDraggableNext } from "vue-draggable-next";
import axios from "@/config/axios";
import TextInput from "@/components/TextInput.vue";
import ButtonLoader from "@/components/ButtonLoader.vue";
import TodoItem from "@/components/TodoItem.vue";

const idModal = ref(null);

const response = reactive({
  isLoading: false,
  error: false,
  data: null,
});

const form = reactive({
  title: "",
});

onMounted(() => {
  fetchTodoList();
});

async function fetchTodoList() {
  try {
    response.isLoading = true;

    const { data } = await axios.get("/todo-list", {
      headers: {
        Authorization: `Bearer ${localStorage.getItem("token")}`,
      },
    });

    const array = [...data?.data];
    const reversedArray = [...data?.data].reverse();

    array.forEach((item, index) => {
      item.order = reversedArray[index].order;
    });

    response.data = array.map((item) => ({ ...item, editable: false }));
  } catch (err) {
    console.error(err);
  } finally {
    response.isLoading = false;
  }
}

async function handlerStatus(id) {
  try {
    response.isLoading = true;

    const current = response?.data?.find((item) => item.id === id);
    const payload = { title: current.title, completed: !current.completed };

    console.log(payload);

    const { data } = await axios.patch(
      `/todo-list/${id}`,
      JSON.stringify(payload)
    );
    console.log(data);
  } catch (err) {
    console.error(err);
  } finally {
    response.isLoading = false;
    fetchTodoList();
  }
}

async function handlerEditable(id) {
  try {
    response.isLoading = true;

    const current = response?.data?.find((item) => item.id === id);
    const payload = { title: current.title, completed: current.completed };
    const { data } = await axios.patch(
      `/todo-list/${id}`,
      JSON.stringify(payload)
    );
  } catch (err) {
    console.error(err);
  } finally {
    response.isLoading = false;
    fetchTodoList();
  }
}

async function handlerDelete(id) {
  try {
    response.isLoading = true;
    const { data } = await axios.delete(`/todo-list/${id}`);
    console.log(data);
  } catch (err) {
    console.error(err);
  } finally {
    response.isLoading = false;
    fetchTodoList();
  }
}

async function handlerSubmit(e) {
  try {
    response.isLoading = true;
    const { error } = await axios.post("/todo-list", JSON.stringify(form));
    idModal.value.close();
    form.title = "";
  } catch (err) {
    response.error = err?.response?.data?.error;
    response.errors = err?.response?.data?.errors;
  } finally {
    response.isLoading = false;
    fetchTodoList();
  }
}

function onEnd(evt) {
  console.log(response.data, evt);
}
</script>

<template>
  <div class="min-h-[85dvh] w-full py-20">
    <div class="container mx-auto relative">
      <button
        class="btn btn-circle md:btn-lg btn-primary fixed right-8 bottom-5 z-10"
        @click="idModal.showModal()"
      >
        <PlusIcon class="size-10" />
      </button>

      <dialog
        ref="idModal"
        @close="console.log('closed')"
        class="modal modal-sm show"
      >
        <div class="modal-box flex flex-col items-center justify-center">
          <h3 class="text-lg font-bold">Tambah ToDo!</h3>

          <TextInput
            label="Judul"
            placeholder="Masukan To Do"
            name="title"
            type="text"
            :errors="response.errors"
            :reactive-state="form"
          />

          <div class="modal-action mt-5">
            <form method="dialog">
              <button class="btn btn-outline" :disabled="response.isLoading">
                Batal
              </button>
            </form>
            <button
              type="button"
              @click.prevent="handlerSubmit"
              :disabled="response.isLoading"
              class="btn btn-primary"
            >
              <ButtonLoader :is-loading="response.isLoading" text="Simpan" />
            </button>
          </div>
        </div>
      </dialog>

      <div class="card w-full max-w-4xl mx-auto bg-base-200">
        <div class="card-body">
          <h2 class="text-2xl font-bold mb-5">Daftar ToDo!</h2>

          <VueDraggableNext
            v-if="response?.data?.length > 0"
            :list="response.data"
            @change="(e) => console.log(e)"
            @end="onEnd"
          >
            <template v-for="(element, idx) in response.data" :key="idx">
              <TodoItem
                :item="element"
                :is-loading="response.isLoading"
                :handler-status="handlerStatus"
                :handler-editable="handlerEditable"
                :handler-delete="handlerDelete"
              />
            </template>
          </VueDraggableNext>
          <div
            v-else
            class="card card-compact flex-row items-center bg-base-100 shadow-md mb-5 py-5"
          >
            <div class="card-body flex-none"></div>
            <div class="card-body flex-1">
              <h1
                class="card-title text-center mx-auto items-center justify-center"
              >
                <template v-if="response.isLoading">
                  <span class="loading loading-spinner"></span>
                  Loading
                </template>
                <template v-else> Tidak ada ToDo! saat ini </template>
              </h1>
            </div>
            <div class="card-body flex-none"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.ghost {
  opacity: 0.5;
  background: #c8ebfb;
}
</style>

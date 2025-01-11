<script setup>
const props = defineProps([
  "label",
  "type",
  "placeholder",
  "name",
  "reactiveState",
  "errors",
]);
</script>

<template>
  <label class="form-control w-full max-w-sm">
    <div class="label">
      <span class="label-text">{{ props.label }}</span>
    </div>
    <input
      :type="props.type"
      :placeholder="props.placeholder"
      class="input input-bordered w-full max-w-sm bg-base-100"
      v-model="props.reactiveState[props.name]"
    />
    <div
      v-if="props.errors && props.errors[props.name]"
      v-for="(msg, idx) in props.errors[props.name]"
      :key="idx"
      class="label"
    >
      <span class="label-text-alt text-error">{{ msg }}</span>
    </div>
  </label>
</template>

<style scoped></style>

<template>
  <div class="relative w-full border border-solid border-slate-300 rounded-xl p-8">
    <h2 class="absolute -top-4 text-xl font-light text-slate-300 tracking-wide bg-gray-50 px-2">{{ title }}</h2>
    <slot></slot>
  </div>
</template>

<script setup>
defineProps({
  title: String
});
</script>

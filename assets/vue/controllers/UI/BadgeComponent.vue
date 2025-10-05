<template>
  <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium inset-ring" :class="computedColor">
    {{ title }}
  </span>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: String,
  title: String,
})

const computedColor = computed(() => {
  const map = {
    valid: "bg-green-400/10 text-green-400 inset-ring-green-500/20",
    warning: "bg-yellow-400/10 text-yellow-400 inset-ring-yellow-500/20",
    danger: "bg-red-400/10 text-red-400 inset-ring-red-500/20"
  };
  return map[props.type] ?? "bg-gray-400/10 text-gray-400 inset-ring-gray-500/20";
});


</script>

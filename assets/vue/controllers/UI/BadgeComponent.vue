<template>
  <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium inset-ring" :class="computedColor">
    {{ title }}
  </span>
</template>

<script setup>
import { ref, computed } from 'vue'

const props = defineProps({
  type: String,
  title: String,
})

const notDefinedClass = ref('bg-gray-400/10 text-gray-400 inset-ring-gray-500/20')
const validClass = ref('bg-green-400/10 text-green-400 inset-ring-green-500/20')
const warningClass = ref('bg-yellow-400/10 text-yellow-400 inset-ring-yellow-500/20')
const dangerClass = ref('bg-red-400/10 text-red-400 inset-ring-red-500/20')

const computedColor = computed(() => {
  const map = {
    valid: validClass.value,
    warning: warningClass.value,
    danger: dangerClass.value
  };
  return map[props.type] ?? notDefinedClass.value;
});


</script>

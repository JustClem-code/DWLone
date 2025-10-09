<template>
  <button :type="buttonType ?? 'button'"
    class="text-sm font-semibold py-2 px-3 rounded-md shadow-xs inset-ring inset-shadow-sm" :class="computedColor"
    @click="emit('click')" :disabled="isDisabled">
    {{ title }}
  </button>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  buttonType: String,
  styleColor: String,
  title: String,
  isDisabled: Boolean,
})

const emit = defineEmits(['click'])

const computedColor = computed(() => {
  const map = {
    empty: "bg-white hover:bg-gray-50 inset-ring-gray-300",
    primary: "text-white bg-blue-500 hover:bg-blue-700 disabled:bg-blue-100 inset-ring-transparent",
    warning: "text-white bg-yellow-500 hover:bg-yellow-700 disabled:bg-yellow-100 inset-ring-transparent",
    danger: "text-white bg-red-500 hover:bg-red-700 disabled:bg-red-100 inset-ring-transparent"
  };
  return map[props.styleColor] ?? "bg-gray-400/10 text-gray-400 inset-ring-gray-500/20";
});


</script>

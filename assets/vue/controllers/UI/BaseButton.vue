<template>
  <button :type="buttonType ?? 'button'"
    class="inline-flex w-full justify-center items-center leading-6 py-2 px-3 transition duration-150 ease-in-out" :class="[computedShape, computedColor]"
    @click="emit('click')" :disabled="isDisabled">
    <AnimateSpin v-if="isLoading"></AnimateSpin>
    <span v-else>{{ title }}</span>
  </button>
</template>

<script setup>
import { computed } from 'vue'
import AnimateSpin from './AnimateSpin.vue';

const props = defineProps({
  buttonType: String,
  styleColor: String,
  title: String,
  isDisabled: Boolean,
  isLoading: Boolean
})

const emit = defineEmits(['click'])

const computedShape = computed(() => {
  return props.styleColor == "flat" ? "text-base" : "text-sm font-semibold rounded-md shadow-xs inset-ring inset-shadow-sm"
})

const computedColor = computed(() => {
  const map = {
    flat: "text-gray-800 dark:text-gray-400 disabled:opacity-25",
    empty: "hover:bg-gray-200/50 dark:text-white dark:bg-gray-700/50 dark:hover:bg-gray-700/70 inset-ring-gray-300 dark:inset-ring-gray-600/50",
    primary: "text-white bg-blue-500 hover:bg-blue-700 disabled:bg-blue-100 inset-ring-transparent",
    warning: "text-white bg-yellow-500 hover:bg-yellow-700 disabled:bg-yellow-100 inset-ring-transparent",
    danger: "text-white bg-red-500 hover:bg-red-700 disabled:bg-red-100 inset-ring-transparent"
  };
  return map[props.styleColor] ?? "bg-gray-400/10 text-gray-400 inset-ring-gray-500/20";
});


</script>

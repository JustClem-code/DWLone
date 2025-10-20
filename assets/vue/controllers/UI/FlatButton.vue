<template>
  <button type="button"
    class="inline-flex w-full justify-center items-center text-base text-gray-800 dark:text-gray-400 leading-6 py-2 px-3 disabled:opacity-25 transition duration-150 ease-in-out"
    @click="emit('click')" :disabled="isDisabled">
    <AnimateSpin v-if="isLoading"></AnimateSpin>
    <span v-else>{{ title }}</span>
  </button>
</template>

<script setup>

const props = defineProps({
  title: String,
  isDisabled: Boolean,
})

const emit = defineEmits(['click'])

</script>

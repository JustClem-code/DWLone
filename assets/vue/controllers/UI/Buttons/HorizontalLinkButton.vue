<template>

    <button type="button" @click="emit('click')"
      class="relative flex justify-center items-center w-full bg-white/40 dark:bg-gray-800/50 border rounded-md shadow-xs dark:shadow-none border-gray-300 dark:border-gray-700/90 hover:border-gray-500 p-1 md:p-4 cursor-pointer"
      :class="{ 'animate-pulse': isLoading }, focused">
      <h3 class="text-[0.50rem] sm:text-base font-light md:font-medium whitespace-nowrap">{{ title }}</h3>

      <div v-if="pingfocused" class="absolute -top-1 -right-1">
        <span class="relative flex size-3">
          <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-blue-400 opacity-75"></span>
          <span class="relative inline-flex size-3 rounded-full bg-blue-500"></span>
        </span>
      </div>

    </button>

</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: String,
  focused: String,
  pingfocused: Boolean,
  isLoading: Boolean,
})

const emit = defineEmits(['click'])

</script>

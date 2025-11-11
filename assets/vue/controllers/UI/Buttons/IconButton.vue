<template>
  <button type="button" id="menu-button-1"
    class="inline-flex size-8 items-center justify-center no-shrink text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
    :class="[border ? 'border' : '', rounded ? 'rounded' : 'rounded-full'  ]"
    aria-haspopup="true" :aria-expanded="isExpanded" @click="emit('click')">
    <slot></slot>
  </button>
</template>

<script setup>

const props = defineProps({
  isExpanded: String,
  border: Boolean,
  rounded: Boolean
})

const emit = defineEmits(['click'])

</script>

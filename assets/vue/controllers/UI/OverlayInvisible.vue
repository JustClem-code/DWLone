<template>
  <div class="fixed top-0 right-0 h-screen w-screen z-10" :class="darker ? 'bg-gray-900/50' : ''" @click="emit('click')"></div>
</template>

<script setup>

const props = defineProps({
  darker: {
    type: Boolean,
    default: false,
  },
  
})

const emit = defineEmits(['click'])

</script>

<template>
  <div>{{ minutes }}:{{ String(seconds).padStart(2, '0') }}</div>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount, defineProps } from 'vue'

const props = defineProps({
  startDate: {
    type: String,
    required: true
  }
})

const minutes = ref(0)
const seconds = ref(0)
let intervalId = null

const updateTimer = () => {
  const start = new Date(props.startDate)
  const now = new Date()
  const diff = Math.floor((now - start) / 1000)

  minutes.value = Math.floor(diff / 60)
  seconds.value = diff % 60
}

onMounted(() => {
  updateTimer()
  intervalId = setInterval(updateTimer, 1000)
})

onBeforeUnmount(() => {
  if (intervalId) {
    clearInterval(intervalId)
  }
})
</script>

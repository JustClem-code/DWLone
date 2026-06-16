import { ref, computed, onBeforeUnmount } from 'vue'

export function useTimer() {
  const startTimestamp = ref(null)
  const durationSeconds = ref(0)
  const intervalId = ref(null)

  const isRunning = computed(() => intervalId.value !== null)

  const startTimer = (timestamp) => {
    if (intervalId.value) return
    startTimestamp.value = timestamp
    updateTimer()
    intervalId.value = setInterval(() => updateTimer(), 1000)
  }

  const stopTimer = () => {
    if (intervalId.value) {
      clearInterval(intervalId.value)
      intervalId.value = null
    }
  }

  const resetTimer = () => {
    stopTimer()
    startTimestamp.value = null
    durationSeconds.value = 0
  }

  const updateTimer = () => {
    if (!startTimestamp.value) return
    const start = new Date(startTimestamp.value)
    const now = new Date()
    const diff = Math.floor((now - start) / 1000)
    durationSeconds.value = diff >= 0 ? diff : 0
  }

  const format = computed(() => {
    if (!durationSeconds.value) {
      return `0'00`
    }
    const minutes = Math.floor(durationSeconds.value / 60)
    const seconds = durationSeconds.value % 60
    return `${minutes}'${String(seconds).padStart(2, '0')}`
  })

  onBeforeUnmount(() => {
    stopTimer()
  })

  return {
    durationSeconds,
    isRunning,
    format,
    startTimer,
    stopTimer,
    resetTimer,
    updateTimer
  }
}

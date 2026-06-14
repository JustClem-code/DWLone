import { ref, computed, onBeforeUnmount } from 'vue'

export function useTimer(startTimestamp = null) {
  const durationSeconds = ref(0)
  const intervalId = ref(null)

  const isRunning = computed(() => intervalId.value !== null)

  const start = () => {
    // if (intervalId.value) return
    update()
    intervalId.value = setInterval(update, 1000)
  }

  const stop = () => {
    if (intervalId.value) {
      clearInterval(intervalId.value)
      intervalId.value = null
    }
  }

  const reset = () => {
    stop()
    durationSeconds.value = 0
  }

  const update = () => {
    // if (!startTimestamp) return
    const start = new Date(startTimestamp)
    const now = new Date()
    const diff = Math.floor((now - start) / 1000)
    durationSeconds.value = diff >= 0 ? diff : 0
  }

  const format = computed(() => {
    const minutes = Math.floor(durationSeconds.value / 60)
    const seconds = durationSeconds.value % 60
    return `${minutes}'${String(seconds).padStart(2, '0')}`
  })

  if (startTimestamp) {
    durationSeconds.value = Math.floor((new Date() - new Date(startTimestamp)) / 1000)
    start()
  }

  onBeforeUnmount(() => {
    stop()
  })

  return {
    durationSeconds,
    isRunning,
    format,
    start,
    stop,
    reset,
    update
  }
}

<template>
  <div class="flex items-center justify-center border rounded-full" :class="size">
    {{ userNameFirstLetter }}
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { userStore } from '../../composables/userStore.js'

const { userName } = userStore()

const props = defineProps({
  size: String
})

const userNameFirstLetter = computed(() => userName.value?.[0]?.toUpperCase() ?? '?')
</script>

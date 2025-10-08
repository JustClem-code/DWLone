<template>
  <button type="button" class="inline-flex items-center gap-2 rounded-md border px-3 py-1.5 text-sm
           bg-white dark:bg-gray-800" @click="toggle" aria-label="Toggle dark mode">
    <span v-if="isDark">🌙</span>
    <span v-else>☀️</span>
    <span>{{ isDark ? 'Dark' : 'Light' }}</span>
  </button>
</template>

<script setup>
import { useTheme } from '../../composables/useTheme.js';
const { isDark, toggle } = useTheme();
</script>

<template>
  <IconButton @click="toggleTheme" :border="true" :rounded="true">
    <DarkIcon v-if="isDark" />
    <LightIcon v-else />
  </IconButton>
</template>

<script setup>
import { useTheme } from '../../composables/useTheme.js';
import IconButton from '../UI/Buttons/IconButton.vue';
import DarkIcon from '../UI/Icons/DarkIcon.vue';
import LightIcon from '../UI/Icons/LightIcon.vue';

const { isDark, toggleTheme } = useTheme();
</script>

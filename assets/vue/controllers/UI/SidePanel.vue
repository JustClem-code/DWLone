<template>
  <div>
    <OverlayInvisible v-show="isOpen" @click="toggleSidePanel" />
    <transition name="panel-enter" enter-active-class="transition-all duration-500 ease-in-out transform"
      enter-from-class="translate-x-full" enter-to-class="translate-x-0"
      leave-active-class="transition-all duration-500 ease-in-out transform" leave-from-class="translate-x-0"
      leave-to-class="translate-x-full">
      <aside v-show="isOpen"
        class="fixed w-full md:w-1/2 h-screen top-0 right-0 bg-gray-100 dark:bg-gray-800 shadow-lg z-50 border-l-0 dark:border-l-1 dark:border-gray-700/90 shadow-2xl">
        <header class="sticky h-24 p-8 top-0 right-0">
          <p class="text-base">{{ title ?? 'Panel title' }}</p>
          <div class="absolute top-4 right-2 block p-2">
            <IconButton @click="toggleSidePanel">
              <CrossIcon />
            </IconButton>
          </div>
        </header>
        <div class="h-8 z-50 w-full absolute top-24 bg-gradient-to-b from-gray-800 to-transparent"></div>
        <div class="h-screen p-8 overflow-y-auto scrollbar-dark">
          <slot></slot>
        </div>
      </aside>
    </transition>
  </div>
</template>


<script setup>
import { ref } from 'vue';
import IconButton from './Buttons/IconButton.vue';
import CrossIcon from './Icons/CrossIcon.vue';
import OverlayInvisible from './OverlayInvisible.vue';

const props = defineProps({
  title: String,
})

const isOpen = ref(false)

const toggleSidePanel = () => {
  isOpen.value = !isOpen.value
  isOpen.value
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");
}

defineExpose({ toggleSidePanel, isOpen});
</script>

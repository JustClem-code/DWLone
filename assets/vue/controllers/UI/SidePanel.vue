<template>
  <div>
    <OverlayInvisible v-show="isOpen" @click="toggleSidePanel" />

    <transition name="panel-enter" enter-active-class="transition-all duration-500 ease-in-out transform"
      enter-from-class="translate-x-full" enter-to-class="translate-x-0"
      leave-active-class="transition-all duration-500 ease-in-out transform" leave-from-class="translate-x-0"
      leave-to-class="translate-x-full">
      <aside v-show="isOpen"
        class="fixed inset-y-0 right-0 w-full bg-gray-100 dark:bg-gray-900 shadow-2xl z-50 border-l-0 dark:border-l dark:border-gray-700/90 flex flex-col" :class="width ?? 'md:w-1/2'">
        <header class="h-24 p-8 flex items-center justify-between shrink-0">
          <p class="text-base">{{ title ?? 'Panel title' }}</p>
          <div class="p-2">
            <IconButton @click="toggleSidePanel">
              <CrossIcon />
            </IconButton>
          </div>
        </header>
        <div
          class="h-8 z-50 w-full absolute top-24 bg-gradient-to-b from-gray-100 to-transparent dark:from-gray-900 dark:to-transparent pointer-events-none">
        </div>

        <div class="flex-1 p-8 overflow-y-auto scrollbar-dark">
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
  width: String,
})

const isOpen = ref(false)

const toggleSidePanel = () => {
  isOpen.value = !isOpen.value
  isOpen.value
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");
}

defineExpose({ toggleSidePanel, isOpen });
</script>

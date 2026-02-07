<template>
  <div>
    <OverlayInvisible v-show="open" @click="toggleSidePanel" />
    <transition name="panel-enter" enter-active-class="transition-all duration-500 ease-in-out transform"
      enter-from-class="translate-x-full" enter-to-class="translate-x-0"
      leave-active-class="transition-all duration-500 ease-in-out transform" leave-from-class="translate-x-0"
      leave-to-class="translate-x-full">
      <aside v-show="open"
        class="fixed w-full md:w-1/2 h-full top-0 right-0 bg-gray-100 dark:bg-gray-800 shadow-lg z-50 border-l-0 dark:border-l-1 dark:border-gray-700/90 p-8 shadow-2xl">
        <header>
          <p class="text-base pb-8">{{ title ?? 'Panel title' }}</p>
          <div class="absolute top-4 right-2 block p-2">
            <IconButton @click="toggleSidePanel">
              <CrossIcon />
            </IconButton>
          </div>
        </header>
        <slot></slot>
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

const open = ref(false)

const toggleSidePanel = () => {
  open.value = !open.value
  open.value
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");
}

defineExpose({ toggleSidePanel });
</script>

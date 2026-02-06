<template>
  <transition v-show="open" name="panel-enter" tag="div"
    enter-active-class="transition-all duration-500 ease-in-out transform" enter-from-class="translate-x-full"
    enter-to-class="translate-x-0" leave-active-class="transition-all duration-500 ease-in-out transform"
    leave-from-class="translate-x-0" leave-to-class="translate-x-full">
    <aside class="absolute w-full h-full top-0 bg-gray-100 dark:bg-gray-800 shadow-lg z-50">
      <p>TEST</p>
      <IconButton @click="toggleSideBar">
        <CrossIcon />
      </IconButton>
    </aside>
  </transition>
</template>

<script setup>
import { ref } from 'vue';
import IconButton from './Buttons/IconButton.vue';
import CrossIcon from './Icons/CrossIcon.vue';

const open = ref(false)

const toggleSideBar = () => {
  open.value = !open.value
  open.value
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");
}

defineExpose({ toggleSideBar });

</script>

<style scoped>
/* aside {
  transition: right 0.3s ease-in-out;
} */
</style>

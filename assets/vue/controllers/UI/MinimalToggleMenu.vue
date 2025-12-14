<template>
  <div class="relative inline-block text-left">
    <IconButton :isExpanded="isOpen.toString()" @click="toggleMenu" >
      <RoundBurgerIcon />
    </IconButton>

    <OverlayInvisible v-show="isOpen" @click="toggleMenu" />
    <div v-show="isOpen"
      class="absolute right-0 mt-2 w-44 origin-top-right z-10 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-0 dark:border-1 dark:border-gray-700/90"
      role="menu">
      <ul>
        <li type="button" v-for="item in items" :key="item.label" @click="onSelect(item)"
          :aria-disabled="item.isDisabled" :tabindex="item.isDisabled ? -1 : 0"
          class="block px-4 py-2 text-sm hover:bg-gray-50 dark:bg-gray-700/50 dark:hover:bg-gray-700/70 first:rounded-t-md last:rounded-b-md"
          :class="item.isDisabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
          {{ item.label }}
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import OverlayInvisible from './OverlayInvisible.vue'
import IconButton from './Buttons/IconButton.vue'
import RoundBurgerIcon from './Icons/RoundBurgerIcon.vue'

const props = defineProps({
  items: Array,
})

const emit = defineEmits(['select'])

const isOpen = ref(false)
const toggleMenu = () => (
  isOpen.value = !isOpen.value
)

const onSelect = (item) => {
  if (!item.isDisabled) {
    emit('select', item.action)
  }
  toggleMenu()
}
</script>

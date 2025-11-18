<template>
  <div class="relative inline-block text-left">

    <button class="flex items-center justify-center rounded-full size-9 border" @click="toggleMenu">{{userNameFirstLetter}}</button>

    <OverlayInvisible v-show="isOpen" @click="toggleMenu" />
    <div v-show="isOpen"
      class="absolute right-0 mt-2 w-44 origin-top-right z-10 rounded-md shadow-lg bg-white dark:bg-gray-800 border border-0 dark:border-1 dark:border-gray-700/90"
      role="menu">
      <ul>
        <a type="button" v-for="item in items" :key="item.name" :aria-disabled="item.isDisabled" @click="item.click"
          class="block px-4 py-2 text-sm hover:bg-gray-50 dark:bg-gray-700/50 dark:hover:bg-gray-700/70 first:rounded-t-md last:rounded-b-md cursor-pointer">
          <div class="flex items-center gap-4">
            <component :is="item.icon" :size="item.sizeIcon" />
            <span>
              {{ item.name }}
            </span>
          </div>
        </a>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import OverlayInvisible from '../UI/OverlayInvisible.vue'
import { userStore } from '../../composables/userStore.js'

const { isUser, userName, setIsUser, setUserName, getIsUser, logOut } = userStore()

const props = defineProps({
  items: Array,
})

const emit = defineEmits(['select'])

const userNameFirstLetter = computed(() => userName.value?.[0]?.toUpperCase() ?? '?')

const isOpen = ref(false)
const toggleMenu = () => (
  isOpen.value = !isOpen.value
)

</script>

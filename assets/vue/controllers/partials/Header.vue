<template>
  <!-- <Sidebar bind:open bind:is_user /> -->
  <nav class="bg-white dark:bg-gray-800/50 border-b border-solid border-gray-200 dark:border-gray-700/90">
    <div class="flex items-center justify-between gap-8 flex-wrap mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8 ">
      <LogoTitle />
      <div class="block md:hidden">
        <IconButton @click="toggleSideBar" :border="true" :rounded="true">
          <BurgerIcon size="size-3" />
        </IconButton>
      </div>
      <div class="w-full flex-grow md:flex md:items-center md:w-auto hidden md:block">
        <NavContent />
      </div>
    </div>
  </nav>
  <header class="relative bg-gray-100 dark:bg-gray-900">
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight">{{ currentItem.name }}</h1>
    </div>
  </header>
  <SideBar class="md:hidden lg:hidden" :open="open" @open="val => toggleSideBar(val)" />
  <NotificationComponent></NotificationComponent>
</template>

<script setup>
import { ref, provide, onMounted, computed } from 'vue'
import NavContent from './NavContent.vue';
import LogoTitle from './LogoTitle.vue';
import SideBar from './SideBar.vue';
import NotificationComponent from '../UI/NotificationComponent.vue';
import IconButton from '../UI/Buttons/IconButton.vue';
import BurgerIcon from '../UI/Icons/BurgerIcon.vue';
import SignOutIcon from '../UI/Icons/SignOutIcon.vue'
import DarkIcon from '../UI/Icons/DarkIcon.vue';
import LightIcon from '../UI/Icons/LightIcon.vue';

import { userStore } from '../../composables/userStore.js'
import { useTheme } from '../../composables/useTheme.js'

const props = defineProps({
  is_user: Boolean,
  user_name: String
})

const { isUser, setIsUser, setUserName, logOut } = userStore()

const { isDark, toggleTheme } = useTheme();

onMounted(() => {
  console.log(`the component is now mounted`)
  setUserName(props.user_name)
  setIsUser(props.is_user)
})

const open = ref(false)

const navigations = computed(() => [
  { name: 'Dashboard', href: '/', show: true },
  { name: 'Yard Truck', href: '/yard/truck', show: isUser.value },
  { name: 'Induction', href: '#', show: isUser.value },
])

const authNavigations = computed(() => [
  { name: 'Sign in', href: '/login', show: !isUser.value },
  { name: 'Sign up', href: '/register', show: !isUser.value },
])

const avatarNavigations = computed(() => [
  { name: isDark.value ? 'Dark theme' : 'Light theme', click: toggleTheme, icon: isDark.value ? DarkIcon : LightIcon, sizeIcon: 'size-6' },
  { name: 'Sign out', click: logOut, icon: SignOutIcon, sizeIcon: 'size-6' },
])

const currentItem = ref(navigations[0])

provide('navigation', { navigations, currentItem, authNavigations, avatarNavigations })

const toggleSideBar = () => {
  open.value = !open.value
  open.value
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");
}

const updateCurrentItem = () => {
  const path = window.location.pathname
  const found = navigations.value.find(item => item.href === path)
  const found2 = authNavigations.value.find(item => item.href === path)

  currentItem.value = (found || found2) || navigations[0] // fallback to first if not found
}

// Initial set
updateCurrentItem()

// Handle browser navigation (back/forward)
window.addEventListener('popstate', () => {
  updateCurrentItem()
})

</script>

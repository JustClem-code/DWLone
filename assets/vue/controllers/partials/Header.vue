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
      <div class="hidden md:block">
        <DarkTheme />
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
import { ref, provide } from 'vue'
import NavContent from './NavContent.vue';
import LogoTitle from './LogoTitle.vue';
import SideBar from './SideBar.vue';
import DarkTheme from './DarkTheme.vue';
import NotificationComponent from '../UI/NotificationComponent.vue';
import IconButton from '../UI/Buttons/IconButton.vue';
import BurgerIcon from '../UI/Icons/BurgerIcon.vue';

const open = ref(false)

const navigations = ref([
  { name: 'Dashboard', href: '/' },
  { name: 'Yard Truck', href: '/yard/truck' },
  { name: 'Induction', href: '#' },
])

const currentItem = ref(navigations[0])

provide('navigation', { navigations, currentItem })

const toggleSideBar = () => {
  open.value = !open.value
  open.value
    ? (document.body.style.overflow = "hidden")
    : (document.body.style.overflow = "auto");
}

const updateCurrentItem = () => {
  const path = window.location.pathname
  const found = navigations.value.find(item => item.href === path)
  currentItem.value = found || navigations[0] // fallback to first if not found
}

// Initial set
updateCurrentItem()

// Handle browser navigation (back/forward)
window.addEventListener('popstate', () => {
  updateCurrentItem()
})

</script>

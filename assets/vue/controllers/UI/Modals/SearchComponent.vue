<template>
  <div class="mx-auto block max-w-2xl overflow-hidden shadow-xl bg-white/40 dark:bg-gray-800/50
    outline outline-offset-2 outline-gray-300 dark:outline-gray-700/90 hover:outline-gray-500
    rounded-lg shadow-xs dark:shadow-none transition-all">

    <div>
      <div class="grid grid-cols-[repeat(1,minmax(0,1fr))]">
        <input id="searchInput" ref="commandInput" type="text" autofocus placeholder="Search..."
          class="col-start-1 row-start-1 h-12 w-full pr-4 pl-11 outline-none text-base bg-white/40 dark:bg-gray-800/50"
          role="combobox" aria-autocomplete="list" autocomplete="off" aria-controls="command-items-1"
          :aria-activedescendant="activeItemId" @focus="isFocused = true" @blur="isFocused = false"
          @keydown="onKeydown">

        <div class="col-start-1 row-start-1 ml-4 self-center pointer-events-none">
          <SearchIcon color="text-gray-200 dark:text-gray-500" title="search bags" />
        </div>

      </div>

      <div ref="itemsContainer" role="listbox"
        class="flex flex-col gap-1 max-h-80 scroll-py-10 scroll-pb-2 overflow-y-auto pt-1 pb-2"
        :class="!isFocused ? 'hidden' : ''">
        <div aria-labelledby="projects-label">
          <h2 class="px-2 font-semibold text-sm">Projects</h2>

          <div v-for="(item, index) in items" :key="item.id" class="px-2 mt-2 text-sm"
            :class="{ 'bg-purple-600': activeIndex === index }">
            <a :href="item.href" :data-type="item.type" class="flex items-center cursor-default px-1 py-2 select-none"
              :id="`item-${item.id}`" role="option" tabindex="-1" :aria-selected="activeIndex === index">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-slot="icon"
                aria-hidden="true" class="size-6 flex-none">
                <path
                  d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"
                  stroke-linecap="round" stroke-linejoin="round"></path>
              </svg>
              <span class="flex-auto ml-3 text-ellipsis whitespace-nowrap overflow-hidden">{{ item.bag.name }}</span>
            </a>
          </div>

        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, watch } from 'vue'
import SearchIcon from '../Icons/SearchIcon.vue'

const props = defineProps({
  items: Array,
})

const isFocused = ref(false)
const activeIndex = ref(-1)
const itemsContainer = ref(null)

const activeItemId = computed(() => {
  if (activeIndex.value < 0 || activeIndex.value >= props.items.length) return ''
  return `item-${props.items[activeIndex.value].id}`
})

function onKeydown(event) {
  if (!isFocused.value) return

  const total = props.items.length
  if (total === 0) return

  if (event.key === 'ArrowDown') {
    event.preventDefault()
    activeIndex.value = (activeIndex.value + 1) % total
    scrollToActiveItem()
  } else if (event.key === 'ArrowUp') {
    event.preventDefault()
    activeIndex.value = (activeIndex.value - 1 + total) % total
    scrollToActiveItem()
  } else if (event.key === 'Enter' && activeIndex.value >= 0) {
    event.preventDefault()
    const item = items.value[activeIndex.value]
    window.location.href = item.href
  } else if (event.key === 'Escape') {
    isFocused.value = false
  }
}

function scrollToActiveItem() {
  nextTick(() => {
    const activeEl = document.getElementById(activeItemId.value)
    if (!activeEl || !itemsContainer.value) return

    activeEl.scrollIntoView({
      block: 'nearest',
      behavior: 'auto'
    })
  })
}

watch(
  () => props.items,
  (val) => {
    console.log('items search watch:', val)
  },
  { immediate: true, deep: true }
)
</script>

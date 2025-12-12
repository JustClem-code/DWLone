<template>
  <div
    class="w-full min-h-70 bg-white dark:bg-gray-800 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
    <dl class="flex flex-col divide-y divide-gray-200 dark:divide-gray-700/90">
      <div class="flex justify-between p-6">
        <div class="flex flex-col">
          <dt class="text-sm">Id</dt>
          <dd>{{ package.id }}</dd>
        </div>
        <transition name="fade-slide" tag="div" enter-active-class="transition-all duration-500 ease-out"
          leave-active-class="transition-all duration-500 ease-in" enter-from-class="opacity-0 -translate-y-4"
          enter-to-class="opacity-100 translate-y-0" leave-from-class="opacity-100 translate-y-0"
          leave-to-class="opacity-0">
          <div v-if="location && !loading" class="self-end">
            <BadgeComponent type="warning" size="lg" :title="location" />
          </div>
        </transition>
      </div>
      <div class="p-6">
        <div class="flex gap-2 items-center">
          <dt>
            <AccountCircleIcon size="size-7" color="text-gray-400" title="client" />
          </dt>
          <dd class="text-sm">
            {{ package.order.customer.firstname }}
            {{ package.order.customer.lastname }}
          </dd>
        </div>
        <div class="flex gap-2 items-center pt-4">
          <dt>
            <AccountCircleIcon size="size-7" color="text-gray-400" title="client" />
          </dt>
          <dd class="text-sm text-gray-400">{{ package.order.address.streetAddress }}</dd>
        </div>
        <div class="flex gap-2 items-center pt-4">
          <dt>
            <AccountCircleIcon size="size-7" color="text-gray-400" title="client" />
          </dt>
          <dd class="text-sm text-gray-400">
            {{ package.order.address.postcode }}
            {{ package.order.address.city }}
          </dd>
        </div>
      </div>
    </dl>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue';
import BadgeComponent from './BadgeComponent.vue';
import AccountCircleIcon from './Icons/AccountCircleIcon.vue';

const props = defineProps({
  package: Object,
  loading: Boolean
});
onMounted(() => {
  console.log('package', props.package);

})

const location = computed(() => {
  return props.package.location?.name
})
</script>

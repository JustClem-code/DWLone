<template>
  <div
    class="w-full bg-white dark:bg-gray-800/50 border border-0 dark:border-1 rounded-md shadow-sm dark:shadow-none dark:border-gray-700/90">
    <dl class="flex flex-col divide-y divide-gray-200 dark:divide-gray-700/90">
      <div class="flex justify-between p-6">
        <div class="flex flex-col">
          <dt class="text-sm">Id</dt>
          <dd class="">{{ package.id }}</dd>
        </div>
        <div class="self-end">
          <BadgeComponent type="warning" :title="package.location" />
        </div>
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
import { onMounted } from 'vue';
import BadgeComponent from './BadgeComponent.vue';
import AccountCircleIcon from './Icons/AccountCircleIcon.vue';

const props = defineProps({
  package: Object
});
onMounted(() => {
  console.log('package', props.package);

})
</script>

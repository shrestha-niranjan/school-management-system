<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: Object
})
</script>

<template>
  <div class="flex justify-center items-center gap-2 flex-wrap">
    <template v-for="(link, key) in links" :key="key">
      <Link
        :class="[
          'h-7 w-7 flex items-center justify-center duration-500 outline-emerald-600 leading-tight rounded-full border hover:border-emerald-600',
          link.active ? 'bg-emerald-600 text-white dark:bg-gray-600' : 'bg-white border border-gray-300',
          link.url === null ? 'hidden' : 'dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white'
        ]"
        :href="link.url || '#'"
        :disabled="link.url === null"
        preserve-state
        preserve-scroll
      >
        <span v-if="key === 0"> &lt; </span>
        <span v-else-if="key === (links.length - 1)"> &gt; </span>
        <span v-else v-html="link.label"></span>
      </Link>
    </template>
  </div>
</template>

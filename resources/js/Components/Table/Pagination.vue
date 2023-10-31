<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
    links: Object
})
</script>

<template>
    <div>
        <div class="flex justify-end flex-wrap">
            <div
                v-for="(link, key) in links"
                class="py-3"
            >
                <Link
                    v-if="link.url === null"
                    v-html="link.label"
                    :class="{
            'bg-white': link.active,
            'rounded-l-lg': key == 0,
            'rounded-r-lg': key == links.length - 1,
            'cursor-text': link.url === null
          }"
                    :disabled="link.url === null ? true : false"
                    :key="key"
                    class="pointer-events-none px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white"
                    href="#"
                    preserve-state
                    preserve-scroll
                />

                <Link
                    v-else
                    v-html="link.label"
                    :class="{
            'bg-white dark:bg-gray-600 border-2 border-blue-600': link.active,
            'rounded-l-lg': key == 0,
            'rounded-r-lg': key == links.length - 1
          }"
                    :href="link.url"
                    class="px-3 py-2 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white"
                    preserve-state
                    preserve-scroll
                />
            </div>
        </div>
    </div>
</template>

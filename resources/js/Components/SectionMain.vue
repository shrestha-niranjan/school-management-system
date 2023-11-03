<script setup>
import { Head } from '@inertiajs/vue3'

defineProps({
    title: {
        type: String,
        default: 'SMS'
    }
})
</script>

<template>
    <Head :title="title" />

    <div class="w-full mx-auto sm:px-6 lg:px-8 space-y-4">
        <slot name="header"></slot>
        <div class="bg-white dark:bg-slate-700 rounded-md p-2">
            <slot name="main"></slot>
        </div>
    </div>
</template>

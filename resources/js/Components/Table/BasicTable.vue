<script setup>
import Pagination from '@/Components/Table/Pagination.vue'

defineProps({
    headers: {
        type: Array,
        default: () => []
    },
    items: {
        type: Object,
        default: () => ({})
    }
})
</script>

<template>
    <table>
        <thead class="border-b bg-primary text-white">
        <tr>
            <th
                class="font-bold text-black"
            >
                SN
            </th>

            <th
                v-for="header in headers"
                :key="header.label"
                class="font-bold text-black"
            >
                {{ header.label }}
            </th>
        </tr>
        </thead>

        <tbody>
        <tr
            class=""
            v-for="(item, index) in items.data"
            :key="item.id"
        >
            <td data-label="SN">{{ index + 1 }}</td>
            <slot :item="item"></slot>
        </tr>

        <tr v-if="items.data.length === 0">
            <td
                :colspan="headers.length + 1"
                class="text-center"
            >
                No data available.
            </td>
        </tr>
        </tbody>
    </table>

    <Pagination :links="items.meta?.links" />
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiPlus } from '@mdi/js'
import { router } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import Tag from 'primevue/tag'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'

const toast = useToast()
const confirm = useConfirm()

defineOptions({
    layout: AuthenticatedLayout
})

defineProps({
    items: {
        type: Object
    },
    columns: Array
})

const handleEdit = id => {
    router.get(route('users.edit', { user: id }))
}

const handleDelete = id => {
    confirm.require({
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
            router.delete(route('users.destroy', { user: id }), {
                onSuccess: () => {
                    toast.add({
                        severity: 'info',
                        summary: 'Confirmed',
                        detail: 'User deleted successfully :)',
                        life: 3000
                    })
                }
            })
        }
    })
}
</script>

<template>
    <ConfirmDialog></ConfirmDialog>

    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">Users</h1>
                <BaseButton
                    class="bg-emerald-600 text-white"
                    label="Create"
                    :icon="mdiPlus"
                    :routeName="route('users.create')"
                />
            </div>
        </template>
        <template #main>
            <DataTable
                :value="items.data"
                :rows="items.meta.per_page"
                paginator
                tableStyle="min-width: 50rem"
            >
                <Column header="#" headerStyle="width:3rem">
                    <template #body="slotProps">
                        {{ slotProps.index + 1 }}
                    </template>
                </Column>

                <Column
                    v-for="(column, index) in columns"
                    :field="column.field"
                    :header="column.header"
                />

                <Column field="role" header="Role" style="min-width: 100px">
                    <template #body="slotProps">
                        <Tag
                            :value="slotProps.data.role"
                            :severity="'success'"
                        />
                    </template>
                </Column>

                <Column :exportable="false" style="min-width: 8rem">
                    <template #body="slotProps">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            class="mr-2 border border-green-500 h-10 w-10 text-green-500"
                            @click="handleEdit(slotProps.data.id)"
                        />

                        <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            class="border border-red-500 h-10 w-10 text-red-500"
                            @click="handleDelete(slotProps.data)"
                        />
                    </template>
                </Column>
            </DataTable>
        </template>
    </SectionMain>
</template>

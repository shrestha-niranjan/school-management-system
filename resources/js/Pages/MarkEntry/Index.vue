<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiPlus } from '@mdi/js'
import { router } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import Tag from 'primevue/tag'
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
    courses: Object
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
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    Manage Mark Entry
                </h1>

                <BaseButton
                    class="bg-emerald-600 text-white"
                    label="Create"
                    :icon="mdiPlus"
                    :routeName="route('mark-entry.create')"
                />
            </div>
        </template>
        <template #main>
            <DataTable
                :value="items"
                tableStyle="min-width: 50rem"
                class="p-datatable-sm"
            >
                <ColumnGroup type="header">
                    <Row>
                        <Column header="#" :rowspan="3" />
                        <Column header="Student Name" :rowspan="3" />
                        <Column
                            v-for="(course, index) in courses"
                            :colspan="3"
                            :header="course"
                            :key="index"
                        />
                    </Row>

                    <Row>
                        <template v-for="course in courses">
                            <Column header="External" field="external"></Column>
                            <Column header="Internal" field="internal"></Column>
                            <Column header="Total" field="total"></Column>
                        </template>
                    </Row>

                    <Row>
                        <Column header="75"></Column>
                        <Column header="25" field="internal"></Column>
                        <Column header="100" field="total"></Column>
                    </Row>
                </ColumnGroup>

                <Column header="#" headerStyle="width:3rem">
                    <template #body="slotProps">
                        {{ slotProps.index + 1 }}
                    </template>
                </Column>

                <Column field="student_name" />
                <Column>
                    <template #body="slotProps">
                        {{ slotProps.data.markEntries }}
                    </template>
                </Column>

                <!-- <Column :exportable="false" style="min-width: 8rem">
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
                </Column> -->
            </DataTable>
        </template>
    </SectionMain>
</template>

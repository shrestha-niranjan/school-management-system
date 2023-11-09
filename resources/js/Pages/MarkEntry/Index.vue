<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiPlus } from '@mdi/js'
import { router } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
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
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    Mark Entries List
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

            <!-- <div class="w-full max-w-screen-xl mx-auto p-4">
                <div class="overflow-x-auto">
                    <table class="w-full border border-collapse text-sm">
                        <thead>
                            <tr>
                                <th class="border p-2" rowspan="3">SN</th>
                                <th class="border p-2" rowspan="3">
                                    Student Name
                                </th>
                                <template v-for="course in courses">
                                    <th class="border p-2" colspan="3">
                                        {{ course }}
                                    </th>
                                </template>

                                <th class="border p-2" rowspan="2">
                                    Grand Total
                                </th>
                            </tr>

                            <tr>
                                <template v-for="course in courses">
                                    <th class="border p-2">Internal</th>
                                    <th class="border p-2">External</th>
                                    <th class="border p-2">Total</th>
                                </template>
                            </tr>

                            <tr>
                                <template v-for="course in courses">
                                    <th class="border p-2">75</th>
                                    <th class="border p-2">25</th>
                                    <th class="border p-2">100</th>
                                </template>

                                <th class="border p-2">700</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(student, index) in students"
                                :key="index"
                            >
                                <td class="border p-2">{{ index + 1 }}</td>
                                <td class="border p-2">{{ student.name }}</td>

                                <template v-for="(course, courseId) in courses">
                                    <td class="border p-2">
                                        {{
                                            student.mark_entries.find(
                                                markEntry =>
                                                    markEntry.course_id ==
                                                    courseId
                                            )
                                                ? student.mark_entries.find(
                                                      markEntry =>
                                                          markEntry.course_id ==
                                                          courseId
                                                  ).internal
                                                : 'N/A'
                                        }}
                                    </td>

                                    <td class="border p-2">
                                        {{
                                            student.mark_entries.find(
                                                markEntry =>
                                                    markEntry.course_id ==
                                                    courseId
                                            )
                                                ? student.mark_entries.find(
                                                      markEntry =>
                                                          markEntry.course_id ==
                                                          courseId
                                                  ).external
                                                : 'N/A'
                                        }}
                                    </td>

                                    <td class="border p-2 font-bold">
                                        {{
                                            student.mark_entries.find(
                                                markEntry =>
                                                    markEntry.course_id ==
                                                    courseId
                                            )
                                                ? student.mark_entries.find(
                                                      markEntry =>
                                                          markEntry.course_id ==
                                                          courseId
                                                  ).total
                                                : 'N/A'
                                        }}
                                    </td>
                                </template>

                                <td class="border p-2 font-bold">
                                    {{
                                        student.mark_entries.reduce(
                                            (total, markEntry) =>
                                                total + markEntry.total,
                                            0
                                        )
                                    }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> -->
        </template>
    </SectionMain>
</template>

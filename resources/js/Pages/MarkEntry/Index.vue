<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiPlus } from '@mdi/js'
import { router, usePage } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'
import { ref } from 'vue'

const toast = useToast()
const confirm = useConfirm()

const showMarksheet = ref(false)
const selectedStudent = ref({})

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
    router.get(route('mark-entry.edit', { student: id }))
}

const handleGenerateMarksheet = id => {
    axios.get(route('generate.marksheet', { student: id })).then(res => {
        showMarksheet.value = true

        selectedStudent.value = res.data
    })
}

const handleDelete = id => {
    confirm.require({
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
            router.delete(route('mark-entry.destroy', { student: id }), {
                onSuccess: () => {
                    toast.add({
                        severity: 'info',
                        summary: 'Confirmed',
                        detail: 'Mark entry deleted successfully :)',
                        life: 3000
                    })
                }
            })
        }
    })
}

const handlePrintBtn = () => {
    const prtHtml = document.getElementById('progressReport').innerHTML

    let stylesHtml = ''
    for (const node of [
        ...document.querySelectorAll('link[rel="stylesheet"], style')
    ]) {
        stylesHtml += node.outerHTML
    }

    const WinPrint = window.open(
        '',
        '',
        'left=300,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0'
    )

    WinPrint.document.write(`<!DOCTYPE html>
        <html>
        <head>
            ${stylesHtml}
            <style>
            @page { size: auto;  margin: 0mm; }
            body  {
                padding: 5rem 1rem;
            }
            </style>
        </head>
        <body>
            ${prtHtml}
        </body>
        </html>`)

    WinPrint.document.close()
    WinPrint.focus()
    WinPrint.print()
    WinPrint.close()
}

const schoolSetting = usePage().props.auth.schoolSetting
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
                            rounded
                            class="mr-2 h-10 w-10"
                            severity="secondary"
                            @click="handleEdit(slotProps.data.id)"
                            v-tooltip.bottom="'Edit'"
                        />

                        <!-- <Button
                            icon="pi pi-trash"
                            outlined
                            rounded
                            severity="danger"
                            class="border border-red-500 h-10 w-10 text-red-500"
                            @click="handleDelete(slotProps.data)"
                        /> -->

                        <Button
                            icon="pi pi-file-export"
                            rounded
                            severity="warning"
                            class="h-10 w-10"
                            @click="handleGenerateMarksheet(slotProps.data.id)"
                            v-tooltip.bottom="'Generate Marksheet'"
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

            <Dialog
                v-model:visible="showMarksheet"
                modal
                :header="' '"
                :style="{ width: '50rem' }"
                :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
            >
                <div id="progressReport" class="bg">
                    <div class="font-bold text-center text-xl uppercase">
                        {{ schoolSetting.name }}
                    </div>

                    <div class="flex justify-center space-x-3 space-y-4 mb-4">
                        <img src="logo.png" alt="logo" class="h-20 w-20" />

                        <div class="text-center">
                            <p class="font-bold">
                                Manthali - 8, Chisapani, Ramechhap
                            </p>
                            <p class="font-bold">Estd.: 2016</p>
                            <p>HALF YEARLY EXAMINATION - 2077</p>
                        </div>
                    </div>

                    <div class="space-y-4 mb-4">
                        <div class="font-bold text-center">PROGRESS REPORT</div>

                        <div class="flex justify-evenly">
                            <div class="font-bold">NAME: Foo Bar</div>

                            <div>
                                <div class="font-bold">CLASS: 10</div>
                                <div class="font-bold">ROLL No: 10</div>
                            </div>
                        </div>
                    </div>

                    <table class="w-full border border-collapse text-sm mb-12">
                        <thead>
                            <tr>
                                <th class="border p-4 -rotate-90" rowspan="2">
                                    SERIAL <br />
                                    NUMBER
                                </th>

                                <th class="border p-4" rowspan="2">SUBJECTS</th>

                                <th class="border p-4 -rotate-90" rowspan="2">
                                    FULL <br />
                                    MARKS
                                </th>

                                <th class="border p-4" colspan="3">
                                    OBTAINED MARKS
                                </th>

                                <th class="border p-4 -rotate-90" rowspan="2">
                                    TOTAL
                                </th>

                                <th class="border p-4 -rotate-90" rowspan="2">
                                    REMARKS
                                </th>
                            </tr>

                            <tr>
                                <th class="border p-4">TH</th>
                                <th class="border p-4">PR</th>
                                <th class="border p-4">CAS</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(
                                    markEntry, index
                                ) in selectedStudent.mark_entries"
                                :key="index"
                            >
                                <td class="border text-center p-2">
                                    {{ ++index }}
                                </td>
                                <td class="border p-2">
                                    {{ markEntry.course.name }}
                                </td>
                                <td class="border text-center p-2">
                                    {{ markEntry.course.full_mark }}
                                </td>

                                <td class="border text-center p-2">
                                    {{ markEntry.external }}
                                </td>

                                <td class="border p-2">
                                    {{ markEntry.internal }}
                                </td>

                                <td class="border p-2"></td>
                                <td class="border p-2">
                                    {{
                                        markEntry.external + markEntry.internal
                                    }}
                                </td>
                                <td class="border p-2"></td>
                            </tr>
                        </tbody>

                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td class="p-4 font-bold">TOTAL</td>
                                <td class="p-4 font-bold">
                                    {{
                                        selectedStudent.mark_entries.reduce(
                                            function (prev, cur) {
                                                return prev + cur.total
                                            },
                                            0
                                        )
                                    }}
                                </td>
                                <td class="p-4 font-bold"></td>
                            </tr>
                        </tfoot>
                    </table>

                    <div class="space-y-2">
                        <div class="flex justify-around">
                            <div>..............................</div>
                            <div></div>
                            <div>..............................</div>
                        </div>

                        <div class="flex justify-around">
                            <div>Class Teacher</div>
                            <div>School Stamp</div>
                            <div>Head Teacher</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <Button
                        label="Print"
                        icon="pi pi-external-link"
                        @click="handlePrintBtn"
                        size="small"
                    />
                </div>
            </Dialog>
        </template>
    </SectionMain>
</template>

<style scoped>
.bg {
    background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg viewBox='0 0 598 245' fill='none' xmlns='http://www.w3.org/2000/svg'%3e%3ctext x='30' y='40' fill='gray'%3eSchool Level Examination%3c/text%3e%3c/svg%3e ");
    background-size: 30px 30px;
}
</style>

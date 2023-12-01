<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiPlus } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'

defineOptions({
    layout: AuthenticatedLayout
})

defineProps({
    students: {
        type: Object
    },
    courses: Object
})
</script>

<template>
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    Mark Ledger
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
            <div class="w-full max-w-screen-xl mx-auto p-4">
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
                                        {{ course.name }}
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
                                    <th class="border p-2">
                                        {{ course.internal_mark }}
                                    </th>
                                    <th class="border p-2">
                                        {{ course.external_mark }}
                                    </th>
                                    <th class="border p-2">
                                        {{ course.full_mark }}
                                    </th>
                                </template>

                                <th class="border p-2">
                                    {{
                                        courses.reduce(
                                            (total, subject) =>
                                                total + subject.full_mark,
                                            0
                                        )
                                    }}
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="(student, index) in students"
                                :key="index"
                            >
                                <td class="border p-2">{{ index + 1 }}</td>
                                <td class="border p-2">{{ student.name }}</td>

                                <template v-for="(course, index) in courses">
                                    <td class="border p-2">
                                        {{
                                            student.mark_entries.find(
                                                markEntry =>
                                                    markEntry.course_id ==
                                                    course.id
                                            )
                                                ? student.mark_entries.find(
                                                      markEntry =>
                                                          markEntry.course_id ==
                                                          course.id
                                                  ).internal
                                                : 'N/A'
                                        }}
                                    </td>

                                    <td class="border p-2">
                                        {{
                                            student.mark_entries.find(
                                                markEntry =>
                                                    markEntry.course_id ==
                                                    course.id
                                            )
                                                ? student.mark_entries.find(
                                                      markEntry =>
                                                          markEntry.course_id ==
                                                          course.id
                                                  ).external
                                                : 'N/A'
                                        }}
                                    </td>

                                    <td class="border p-2 font-bold">
                                        {{
                                            student.mark_entries.find(
                                                markEntry =>
                                                    markEntry.course_id ==
                                                    course.id
                                            )
                                                ? student.mark_entries.find(
                                                      markEntry =>
                                                          markEntry.course_id ==
                                                          course.id
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

                            <tr v-if="!students.length">
                                <td class="text-center" colspan="3">
                                    No records found.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>
    </SectionMain>
</template>

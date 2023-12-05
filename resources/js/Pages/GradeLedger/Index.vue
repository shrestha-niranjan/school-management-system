<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiPlus } from '@mdi/js'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'

defineOptions({
    layout: AuthenticatedLayout
})

const props = defineProps({
    students: {
        type: Object
    },
    courses: Object
})

function checkExternalGP (mark) {
    if (mark >= 67.5) {
        return 'A+'
    } else if (mark >= 60) {
        return 'A'
    } else if (mark >= 52.5) {
        return 'B+'
    } else if (mark >= 45) {
        return 'B'
    } else if (mark >= 37.5) {
        return 'C+'
    } else if (mark >= 30) {
        return 'C'
    } else if (mark >= 26.25) {
        return 'D'
    } else if (mark < 26.25) {
        return 'NG'
    }
}

function checkInternalGP (mark) {
    if (mark >= 22.5) {
        return 'A+'
    } else if (mark >= 20) {
        return 'A'
    } else if (mark >= 17.5) {
        return 'B+'
    } else if (mark >= 15) {
        return 'B'
    } else if (mark >= 12.5) {
        return 'C+'
    } else if (mark >= 10) {
        return 'C'
    } else if (mark >= 8.75) {
        return 'D'
    } else if (mark < 8.75 && mark > 0) {
        return 'NG'
    } else if (mark === 0) {
        return 0
    }
}

function checkAvgGP (mark) {
    if (mark >= 90) {
        return 'A+'
    } else if (mark >= 80) {
        return 'A'
    } else if (mark >= 70) {
        return 'B+'
    } else if (mark >= 60) {
        return 'B'
    } else if (mark >= 50) {
        return 'C+'
    } else if (mark >= 40) {
        return 'C'
    } else if (mark >= 35) {
        return 'D'
    } else if (mark < 35) {
        return 'NG'
    }
}

function getGrade (gp) {
    if (gp === 'A+') {
        return '4.0'
    } else if (gp === 'A') {
        return '3.6'
    } else if (gp === 'B+') {
        return '3.2'
    } else if (gp === 'B') {
        return '2.8'
    } else if (gp === 'C+') {
        return '2.4'
    } else if (gp === 'C') {
        return '2.0'
    } else if (gp === 'D') {
        return '1.6'
    } else if (gp === 'NG') {
        return '0'
    }

    return '-'
}

function calculateGPA (student) {
    let total = 0

    student.mark_entries.forEach(mark => {
        total += getGrade(checkAvgGP(mark.total)) * mark.course.credit_hour
    })

    return (
        total /
        props.courses.reduce((total, course) => total + course.credit_hour, 0)
    ).toFixed(2)
}
</script>

<template>
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    Grade Ledger
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
                                    <th class="border p-2" colspan="4">
                                        {{ course.name }}
                                    </th>
                                </template>

                                <th class="border p-2" rowspan="3">GPA</th>
                            </tr>

                            <tr>
                                <template v-for="course in courses">
                                    <th class="border p-2">TH</th>
                                    <th class="border p-2">PR</th>
                                    <th class="border p-2">Total</th>
                                    <th class="border p-2"></th>
                                </template>
                            </tr>

                            <tr>
                                <template v-for="course in courses">
                                    <th class="border p-2">G</th>
                                    <th class="border p-2">G</th>
                                    <th class="border p-2">FG</th>
                                    <th class="border p-2">GP</th>
                                </template>
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
                                                ? checkExternalGP(
                                                      student.mark_entries.find(
                                                          markEntry =>
                                                              markEntry.course_id ==
                                                              course.id
                                                      ).external
                                                  )
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
                                                ? checkInternalGP(
                                                      student.mark_entries.find(
                                                          markEntry =>
                                                              markEntry.course_id ==
                                                              course.id
                                                      ).internal
                                                  )
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
                                                ? checkAvgGP(
                                                      student.mark_entries.find(
                                                          markEntry =>
                                                              markEntry.course_id ==
                                                              course.id
                                                      ).total
                                                  )
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
                                                ? getGrade(
                                                      checkAvgGP(
                                                          student.mark_entries.find(
                                                              markEntry =>
                                                                  markEntry.course_id ==
                                                                  course.id
                                                          ).total
                                                      )
                                                  )
                                                : 'N/A'
                                        }}
                                    </td>
                                </template>

                                <td class="border p-2 font-bold">
                                    {{ calculateGPA(student) }}

                                    <!-- {{
                                        student.mark_entries.for(
                                            (total, markEntry) =>
                                                total + markEntry.total,
                                            0
                                        )
                                    }} -->
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

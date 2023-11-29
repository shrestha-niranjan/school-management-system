<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({
    grades: Object
})

const schoolSetting = usePage().props.auth.schoolSetting

const form = useForm({
    name: schoolSetting.name,
    address: schoolSetting.address,
    established_at: schoolSetting.established_at,
    academic_year: parseInt(schoolSetting.academic_year),
    grade_id: schoolSetting.grade_id
})

const currentYear = new Date().getFullYear()

const academicYears = ref(
    generateYears(form.established_at, currentYear + 56 + 100)
)

function generateYears (start, end) {
    const years = []
    for (let year = start; year <= end; year++) {
        years.push(year)
    }
    return years
}

const selectedGrade = props.grades.find(grade => grade.id === form.grade_id)

const gradeCourses = selectedGrade.courses
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                School Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your schoool's basic informations. Note that these
                changes will impact whole system. So be careful.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('settings.update'))"
            v-focustrap
            class="mt-8 space-y-2"
        >
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <span class="p-float-label">
                        <InputText
                            id="name"
                            autofocus
                            v-model="form.name"
                            class="w-full"
                            :class="
                                form.errors.name && !form.name && 'p-invalid'
                            "
                        />
                        <label for="name">Name</label>
                    </span>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors.name &&
                                !form.name &&
                                form.errors.name) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>

                <div>
                    <span class="p-float-label">
                        <InputText
                            id="address"
                            autofocus
                            v-model="form.address"
                            class="w-full"
                            :class="
                                form.errors.address &&
                                !form.address &&
                                'p-invalid'
                            "
                        />
                        <label for="address">Address</label>
                    </span>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors.address &&
                                !form.address &&
                                form.errors.address) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>

                <div>
                    <span class="p-float-label">
                        <InputNumber
                            v-model="form.established_at"
                            :class="
                                form.errors.established_at &&
                                !form.established_at &&
                                'p-invalid'
                            "
                            :useGrouping="false"
                            id="established_at"
                            class="w-full"
                            showButtons
                        />
                        <label for="established_at">Establised At</label>
                    </span>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors.established_at &&
                                !form.established_at &&
                                form.errors.established_at) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>

                <div class="p-float-label">
                    <Dropdown
                        v-model="form.academic_year"
                        inputId="dd-year"
                        :options="academicYears"
                        class="w-full md:w-14rem"
                    />
                    <label for="dd-year">Select a Academic Year</label>
                </div>

                <div class="p-float-label">
                    <Dropdown
                        v-model="form.grade_id"
                        inputId="dd-grade"
                        :options="grades"
                        option-label="name"
                        option-value="id"
                        class="w-full md:w-14rem"
                    />
                    <label for="dd-grade">Select a Grade</label>
                </div>
            </div>

            <Divider class="p-4" />

            <header class="pb-8">
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    Courses
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    <!-- TODO::axios for grades courses -->
                    Update your courses for grade {{ selectedGrade.name }}
                </p>
            </header>

            <div
                v-for="(course, index) in gradeCourses"
                :key="index"
                class="grid grid-cols-1 sm:grid-cols-12 gap-4"
            >
                <div class="col-span-4">
                    <span class="p-float-label">
                        <InputText
                            autofocus
                            v-model="course.name"
                            class="w-full"
                            :class="
                                form.errors.name && !form.name && 'p-invalid'
                            "
                        />
                        <label for="name">Name</label>
                    </span>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors.name &&
                                !form.name &&
                                form.errors.name) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>

                <div class="col-span-3">
                    <span class="p-float-label">
                        <InputNumber
                            v-model="course.external_mark"
                            class="w-full"
                            :class="
                                form.errors['marks.' + index + '.external'] &&
                                !course.external_mark &&
                                'p-invalid'
                            "
                            :use-grouping="false"
                            :minFractionDigits="2"
                            :maxFractionDigits="2"
                            :min="0"
                            :max="100"
                        />
                        <label for="external_marks">External Marks</label>
                    </span>

                    <small class="p-error" id="text-error">
                        {{
                            (form?.errors['marks.' + index + '.external'] &&
                                !course.external_mark &&
                                form?.errors['marks.' + index + '.external']) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>

                <div class="col-span-3">
                    <span class="p-float-label">
                        <InputNumber
                            v-model="course.internal_mark"
                            class="w-full"
                            :class="
                                form.errors['marks.' + index + '.internal'] &&
                                !course.internal_mark &&
                                'p-invalid'
                            "
                            :use-grouping="false"
                            :minFractionDigits="2"
                            :maxFractionDigits="2"
                            :min="0"
                            :max="100"
                        />

                        <label for="internal_marks">Internal Marks</label>
                    </span>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors['marks.' + index + '.internal'] &&
                                !course.internal_mark &&
                                form.errors['marks.' + index + '.internal']) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>

                <!-- <div class="col-span-2 flex justify-around">
                <Button
                    @click="add"
                    icon="pi pi-plus"
                    severity="success"
                    rounded
                    aria-label="Add"
                    class="h-10 w-10"
                />

                <Button
                    v-if="index !== 0"
                    @click="remove(index)"
                    icon="pi pi-times"
                    severity="danger"
                    rounded
                    aria-label="Remove"
                    class="h-10 w-10"
                />
            </div> -->
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Saved.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>

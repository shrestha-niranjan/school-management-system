<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'

const toast = useToast()
const confirm = useConfirm()

const props = defineProps({
    grades: Object
})

const visible = ref(false)

const schoolSetting = usePage().props.auth.schoolSetting

const form = useForm({
    name: schoolSetting.name,
    address: schoolSetting.address,
    established_at: schoolSetting.established_at,
    academic_year: parseInt(schoolSetting.academic_year),
    grade_id: schoolSetting.grade_id,
    courses: []
})

const courseForm = useForm({
    id: '',
    name: '',
    internal_mark: 0,
    external_mark: 0
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

const gradeCourses = ref()

const handleFormSubmit = () => {
    form.courses = gradeCourses

    form.patch(route('settings.update'), {
        preserveScroll: true
    })
}

const handleGradeChange = () => {
    let courses = props.grades.find(grade => grade.id === form.grade_id).courses

    if (courses.length) {
        gradeCourses.value = courses
    } else {
        gradeCourses.value = [
            {
                id: null,
                name: '',
                internal_mark: 0,
                external_mark: 0
            }
        ]
    }
}

const handleAddCourse = () => {
    courseForm.id
        ? courseForm.patch(route('courses.update', { course: courseForm.id }), {
              onSuccess: () => {
                  toast.add({
                      severity: 'info',
                      summary: 'Updated',
                      detail: 'Course updated successfully :)',
                      life: 3000
                  })

                  courseForm.reset()
              },
              onFinish: () => {
                  visible.value = false

                  handleGradeChange()

                  courseForm.reset()
              },
              preserveScroll: true
          })
        : courseForm.post(route('courses.store'), {
              onSuccess: () => {
                  toast.add({
                      severity: 'info',
                      summary: 'Created',
                      detail: 'Course created successfully :)',
                      life: 3000
                  })

                  courseForm.reset()
              },
              onFinish: () => {
                  courseForm.reset()

                  visible.value = false

                  handleGradeChange()
              },
              preserveScroll: true
          })
}

const handleEditCourse = id => {
    visible.value = true

    let selectedCourse = gradeCourses.value.find(course => course.id === id)

    courseForm.name = selectedCourse.name
    courseForm.id = selectedCourse.id
    courseForm.external_mark = selectedCourse.external_mark
    courseForm.internal_mark = selectedCourse.internal_mark
}

const handleDeleteCourse = id => {
    confirm.require({
        message: 'Are you sure you want to proceed?',
        header: 'Confirmation',
        icon: 'pi pi-exclamation-triangle',
        accept: () => {
            courseForm.delete(route('courses.destroy', { course: id }), {
                onSuccess: () => {
                    toast.add({
                        severity: 'info',
                        summary: 'Deleted',
                        detail: 'Course deleted successfully :)',
                        life: 3000
                    })

                    handleGradeChange()
                }
            })
        }
    })
}

const showCourseAddModal = () => {
    visible.value = true

    courseForm.reset()
}

onMounted(() => {
    let courses = props.grades.find(grade => grade.id === form.grade_id).courses

    if (courses.length) {
        gradeCourses.value = courses
    }
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                School Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your school's basic informations. Note that these changes
                will impact whole system. So be careful.
            </p>
        </header>

        <form @submit.prevent="handleFormSubmit" class="mt-8">
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
                        @change="handleGradeChange"
                        option-label="name"
                        option-value="id"
                        class="w-full md:w-14rem"
                    />
                    <label for="dd-grade">Select a Grade</label>
                </div>
            </div>

            <Divider class="p-4" />

            <header class="pb-8 flex justify-between">
                <div>
                    <h2
                        class="text-lg font-medium text-gray-900 dark:text-gray-100"
                    >
                        Courses
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Update your courses with marks.
                    </p>
                </div>

                <Button
                    label="Add"
                    icon="pi pi-plus"
                    size="small"
                    class="h-10 w-30"
                    @click="showCourseAddModal"
                />
            </header>

            <div
                v-for="(course, index) in gradeCourses"
                :key="index"
                class="grid grid-cols-1 sm:grid-cols-12 gap-4"
            >
                <div class="col-span-4 mb-8">
                    <span class="p-float-label">
                        <Dropdown
                            disabled
                            v-model="course.id"
                            class="w-full"
                            :options="gradeCourses"
                            option-label="name"
                            option-value="id"
                        />
                        <label for="name">Name</label>
                    </span>
                </div>

                <div class="col-span-3 mb-8">
                    <span class="p-float-label">
                        <InputNumber
                            v-model="course.external_mark"
                            disabled
                            class="w-full"
                            :use-grouping="false"
                            :minFractionDigits="2"
                            :maxFractionDigits="2"
                            :min="0"
                            :max="100"
                        />
                        <label for="external_marks">External Marks</label>
                    </span>
                </div>

                <div class="col-span-3 mb-8">
                    <span class="p-float-label">
                        <InputNumber
                            v-model="course.internal_mark"
                            disabled
                            class="w-full"
                            :use-grouping="false"
                            :minFractionDigits="2"
                            :maxFractionDigits="2"
                            :min="0"
                            :max="100"
                        />

                        <label for="internal_marks">Internal Marks</label>
                    </span>
                </div>

                <div class="col-span-2 flex justify-around">
                    <Button
                        @click="handleEditCourse(course.id)"
                        icon="pi pi-file-edit"
                        severity="secondary"
                        rounded
                        aria-label="Edit"
                        class="h-10 w-10"
                    />

                    <Button
                        @click="handleDeleteCourse(course.id)"
                        icon="pi pi-times"
                        severity="danger"
                        rounded
                        aria-label="Remove"
                        class="h-10 w-10"
                    />
                </div>
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

    <Dialog
        v-model:visible="visible"
        modal
        header="Header"
        :style="{ width: '50rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }"
    >
        <template #header>
            <div class="font-bold">
                {{ courseForm.id ? 'Edit' : 'Add' }} Course
            </div>
        </template>

        <div class="mt-8 space-y-4">
            <div>
                <span class="p-float-label">
                    <InputText
                        id="courseName"
                        autofocus
                        v-model="courseForm.name"
                        class="w-full"
                        :class="
                            courseForm.errors.name &&
                            !courseForm.name &&
                            'p-invalid'
                        "
                    />
                    <label for="courseName">Course Name</label>
                </span>

                <small class="p-error" id="text-error">
                    {{
                        (courseForm.errors.name &&
                            !courseForm.name &&
                            courseForm.errors.name) ||
                        '&nbsp;'
                    }}
                </small>
            </div>

            <div class="col-span-3 mb-8">
                <span class="p-float-label">
                    <InputNumber
                        v-model="courseForm.internal_mark"
                        class="w-full"
                        :class="
                            courseForm.errors.internal_mark &&
                            !courseForm.internal_mark &&
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
                        (courseForm.errors.internal_mark &&
                            !course.internal_mark &&
                            courseForm.errors.internal_mark) ||
                        '&nbsp;'
                    }}
                </small>
            </div>

            <div class="col-span-3 mb-8">
                <span class="p-float-label">
                    <InputNumber
                        v-model="courseForm.external_mark"
                        class="w-full"
                        :class="
                            courseForm.errors.external_mark &&
                            !courseForm.external_mark &&
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
                        (courseForm.errors.external_mark &&
                            !course.external_mark &&
                            courseForm.errors.external_mark) ||
                        '&nbsp;'
                    }}
                </small>
            </div>
        </div>

        <template #footer>
            <Button
                :label="courseForm.id ? 'Edit' : 'Add'"
                icon="pi pi-check"
                @click="handleAddCourse"
                autofocus
            />
        </template>
    </Dialog>
</template>

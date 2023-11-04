<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import { mdiArrowLeft, mdiStore } from '@mdi/js'
import { useToast } from 'primevue/usetoast'

const toast = useToast()

defineOptions({
    layout: AuthenticatedLayout
})

const props = defineProps({
    isEdit: {
        default: false,
        type: Boolean
    },
    item: {
        type: Object,
        default: [{}]
    },
    students: {
        type: Object,
        default: [{}]
    },
    courses: {
        type: Object,
        default: [{}]
    }
})

const form = useForm({
    student_id:
        props.isEdit && props.item.student_id ? props.item.student_id : '',
    marks: [
        {
            course_id: '',
            external: null,
            internal: null
        }
    ]
})
const onSubmit = () => {
    props.isEdit
        ? form.put(route('mark-entry.update', { user: props.item.id }), {
              onSuccess: () => {
                  toast.add({
                      severity: 'success',
                      summary: 'Success',
                      detail: 'User updated successfully.',
                      life: 3000
                  })
              }
          })
        : form.post(route('mark-entry.store'), {
              onSuccess: () => {
                  toast.add({
                      severity: 'success',
                      summary: 'Success',
                      detail: 'User created successfully.',
                      life: 3000
                  })
              }
          })
}

const add = () => {
    form.marks.push({
        course_id: '',
        external: null,
        internal: null
    })
}

const remove = index => {
    form.marks.splice(index, 1)
}
</script>

<template>
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    {{ isEdit ? 'Edit' : 'Create' }} a Mark Entry
                </h1>
                {{ errors }}
                <BaseButton
                    class="bg-red-600 text-white"
                    label="Back"
                    :icon="mdiArrowLeft"
                    :routeName="route('users.index')"
                />
            </div>
        </template>
        <template #main>
            <form @submit.prevent="onSubmit" class="space-y-2 p-8">
                <!-- BEGIN::Student section -->
                <div>
                    <div class="p-float-label">
                        <Dropdown
                            v-model="form.student_id"
                            :options="students"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Student"
                            class="w-full md:w-14rem"
                            filter
                            :class="form.errors.student_id && 'p-invalid'"
                        />

                        <label for="dd-student">Select a Student</label>
                    </div>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors.student_id &&
                                !form.student_id &&
                                form.errors.student_id) ||
                            '&nbsp;'
                        }}
                    </small>
                </div>
                <!-- END::Student section -->

                <!-- BEGIN::Mark Entry -->
                <div
                    v-for="(mark, index) in form.marks"
                    class="grid grid-cols-1 sm:grid-cols-12 gap-4"
                >
                    <div class="col-span-4">
                        <div class="p-float-label">
                            <Dropdown
                                v-model="mark.course_id"
                                :options="courses"
                                optionLabel="name"
                                optionValue="id"
                                placeholder="Select a Course"
                                class="w-full md:w-14rem"
                                filter
                                :class="
                                    form.errors[
                                        'marks.' + index + '.course_id'
                                    ] && 'p-invalid'
                                "
                            />

                            <label for="dd-student">Select a Course</label>
                        </div>

                        <small class="p-error" id="text-error">
                            {{
                                form?.errors['marks.' + index + '.course_id'] ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>

                    <div class="col-span-3">
                        <span class="p-float-label">
                            <InputNumber
                                id="external_marks"
                                v-model="mark.external"
                                class="w-full"
                                :class="
                                    form.errors[
                                        'marks.' + index + '.external'
                                    ] &&
                                    !mark.external &&
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
                                form?.errors['marks.' + index + '.external'] ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>

                    <div class="col-span-3">
                        <span class="p-float-label">
                            <InputNumber
                                id="internal_marks"
                                v-model="mark.internal"
                                class="w-full"
                                :class="
                                    form.errors[
                                        'marks.' + index + '.internal'
                                    ] &&
                                    !mark.internal &&
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
                                form?.errors['marks.' + index + '.internal'] ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>

                    <div class="col-span-2 flex justify-around">
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
                    </div>
                </div>
                <!-- END::Mark Entry -->

                <div class="flex justify-end">
                    <BaseButton
                        class="bg-sky-600 text-white"
                        label="Save"
                        :icon="mdiStore"
                        type="submit"
                    />
                </div>
            </form>
        </template>
    </SectionMain>
</template>

<style>
.p-password-input {
    width: 100%;
}
</style>

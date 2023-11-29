<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import { mdiArrowLeft, mdiContentSavePlus } from '@mdi/js'
import { useToast } from 'primevue/usetoast'
import { computed, ref } from 'vue'
const toast = useToast()

defineOptions({
    layout: AuthenticatedLayout
})

let today = new Date()
let month = today.getMonth()
let year = today.getFullYear()
let nextMonth = month === 11 ? 0 : month + 1
let nextYear = nextMonth === 0 ? year + 1 : year

const maxDate = ref(new Date())

maxDate.value.setMonth(nextMonth)
maxDate.value.setFullYear(nextYear)

const props = defineProps({
    isEdit: {
        default: false,
        type: Boolean
    },
    item: {
        type: Object,
        default: [{}]
    }
})

const form = useForm({
    name: props.isEdit && props.item.name ? props.item.name : '',
    dob: props.isEdit && props.item.dob ? props.item.dob : '',
    father_name:
        props.isEdit && props.item.father_name ? props.item.father_name : '',
    mother_name:
        props.isEdit && props.item.mother_name ? props.item.mother_name : '',
    address: props.isEdit && props.item.address ? props.item.address : ''
})
const onSubmit = () => {
    props.isEdit
        ? form.put(route('students.update', { student: props.item.id }), {
              onSuccess: () => {
                  toast.add({
                      severity: 'success',
                      summary: 'Success',
                      detail: 'Student updated successfully.',
                      life: 3000
                  })
              }
          })
        : form.post(route('students.store'), {
              onSuccess: () => {
                  toast.add({
                      severity: 'success',
                      summary: 'Success',
                      detail: 'Student created successfully.',
                      life: 3000
                  })
              }
          })
}

const studentId = computed(() => {
    return props.roles.find(({ name }) => name === 'Student').id
})
</script>

<template>
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    {{ isEdit ? 'Edit' : 'Create' }} a Student
                </h1>

                <BaseButton
                    class="bg-red-600 text-white"
                    label="Back"
                    :icon="mdiArrowLeft"
                    :routeName="route('students.index')"
                />
            </div>
        </template>
        <template #main>
            <form @submit.prevent="onSubmit" class="space-y-2 p-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- BEGIN::Name section -->
                    <div>
                        <span class="p-float-label">
                            <InputText
                                id="name"
                                v-model="form.name"
                                class="w-full"
                                :class="
                                    form.errors.name &&
                                    !form.name &&
                                    'p-invalid'
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
                    <!-- END::Name section -->

                    <!-- BEGIN::DOB section -->
                    <div>
                        <span class="p-float-label">
                            <Calendar
                                id="dob"
                                v-model="form.dob"
                                class="w-full"
                                showIcon
                                :manualInput="false"
                                :maxDate="maxDate"
                                :class="
                                    form.errors.dob && !form.dob && 'p-invalid'
                                "
                            />
                            <label for="dob">DOB</label>
                        </span>

                        <small class="p-error" id="text-error">
                            {{
                                (form.errors.dob &&
                                    !form.dob &&
                                    form.errors.dob) ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>
                    <!-- END::DOB section -->

                    <!-- BEGIN::Father Name section -->
                    <div>
                        <span class="p-float-label">
                            <InputText
                                id="father_name"
                                v-model="form.father_name"
                                class="w-full"
                                :class="
                                    form.errors.father_name &&
                                    !form.father_name &&
                                    'p-invalid'
                                "
                            />
                            <label for="father_name">Father Name</label>
                        </span>

                        <small class="p-error" id="text-error">
                            {{
                                (form.errors.father_name &&
                                    !form.father_name &&
                                    form.errors.father_name) ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>
                    <!-- END::Father Name section -->

                    <!-- BEGIN::Mother Name section -->
                    <div>
                        <span class="p-float-label">
                            <InputText
                                id="mother_name"
                                v-model="form.mother_name"
                                class="w-full"
                                :class="
                                    form.errors.mother_name &&
                                    !form.mother_name &&
                                    'p-invalid'
                                "
                            />
                            <label for="mother_name">Mother Name</label>
                        </span>

                        <small class="p-error" id="text-error">
                            {{
                                (form.errors.mother_name &&
                                    !form.mother_name &&
                                    form.errors.mother_name) ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>
                    <!-- END::Mother Name section -->

                    <!-- BEGIN::Address section -->
                    <div>
                        <span class="p-float-label">
                            <InputText
                                id="address"
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
                    <!-- END::Address section -->
                </div>

                <div class="flex justify-end">
                    <BaseButton
                        class="bg-sky-600 text-white"
                        label="Save"
                        :icon="mdiContentSavePlus"
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

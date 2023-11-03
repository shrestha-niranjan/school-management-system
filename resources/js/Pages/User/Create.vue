<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import BaseButton from '@/Components/BaseButton.vue'
import FormControl from '@/Components/FormControl.vue'
import SectionMain from '@/Components/SectionMain.vue'
import FormField from '@/Components/FormField.vue'
import { mdiArrowLeft, mdiStore } from '@mdi/js'
import Dropdown from 'primevue/dropdown'
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
    roles: {
        type: Object,
        default: [{}]
    }
})

const form = useForm({
    name: props.isEdit && props.item.name ? props.item.name : '',
    email: props.isEdit && props.item.email ? props.item.email : '',
    role: props.isEdit && props.item.roles ? props.item.roles[0].name : '',
    password: props.isEdit && props.item.password ? props.item.password : '',
    password_confirmation:
        props.isEdit && props.item.password_confirmation
            ? props.item.password_confirmation
            : ''
})
const onSubmit = () => {
    props.isEdit
        ? form.put(route('users.update', { user: props.item.id }), {
              onSuccess: () => {
                  toast.add({
                      severity: 'success',
                      summary: 'Success',
                      detail: 'User updated successfully.',
                      life: 3000
                  })
              }
          })
        : form.post(route('users.store'), {
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
</script>

<template>
    <SectionMain>
        <template #header>
            <div class="flex justify-between items-center rounded">
                <h1 class="text-lg font-semibold dark:text-white">
                    User / {{ isEdit ? 'Edit' : 'Create' }}
                </h1>

                <BaseButton
                    class="bg-red-600 text-white"
                    label="Back"
                    :icon="mdiArrowLeft"
                    :routeName="route('users.index')"
                />
            </div>
        </template>
        <template #main>
            <form @submit.prevent="onSubmit" class="space-y-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <FormField label="Name">
                        <FormControl
                            :error="form.errors.name"
                            v-model="form.name"
                            placeholder="Name"
                        />
                    </FormField>
                    <FormField label="Email">
                        <FormControl
                            :error="form.errors.email"
                            v-model="form.email"
                            placeholder="Email"
                        />
                    </FormField>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <FormField label="Password">
                        <FormControl
                            :error="form.errors.password"
                            v-model="form.password"
                            placeholder="Password"
                        />
                    </FormField>
                    <FormField label="Password Confirmation">
                        <FormControl
                            :error="form.errors.password_confirmation"
                            v-model="form.password_confirmation"
                            placeholder="Password Confirmation"
                        />
                    </FormField>
                </div>
                <div class="grid grid-cols-1">
                    <FormField label="Role">
                        <Dropdown
                            v-model="form.role"
                            :options="roles"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Role"
                            class="w-full md:w-14rem"
                        />
                    </FormField>
                    <InputError class="mt-2" :message="form.errors.role" />
                </div>

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

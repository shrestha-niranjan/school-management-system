<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
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
    },
    breadcrumbs: Array
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
                <Breadcrumb
                    :home="breadcrumbs.home"
                    :model="breadcrumbs.items"
                />

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

                    <!-- BEGIN::Email section -->
                    <div>
                        <span class="p-float-label">
                            <InputText
                                id="email"
                                v-model="form.email"
                                class="w-full"
                                :class="
                                    form.errors.email &&
                                    !form.email &&
                                    'p-invalid'
                                "
                            />
                            <label for="email">Email</label>
                        </span>

                        <small class="p-error" id="text-error">
                            {{
                                (form.errors.email &&
                                    !form.email &&
                                    form.errors.email) ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>
                    <!-- END::Email section -->
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- BEGIN::Password section -->
                    <div>
                        <span class="p-float-label">
                            <Password
                                id="password"
                                v-model="form.password"
                                :class="
                                    form.errors.password &&
                                    !form.password &&
                                    'p-invalid'
                                "
                                class="w-full"
                                toggle-mask
                            >
                                <template #header>
                                    <h6>Pick a password</h6>
                                </template>
                                <template #footer>
                                    <Divider />
                                    <p class="mt-2">Suggestions</p>
                                    <ul
                                        class="pl-2 ml-2 mt-0"
                                        style="line-height: 1.5"
                                    >
                                        <li>At least one lowercase</li>
                                        <li>At least one uppercase</li>
                                        <li>At least one numeric</li>
                                        <li>Minimum 8 characters</li>
                                    </ul>
                                </template>
                            </Password>

                            <label for="password">Password</label>
                        </span>

                        <small class="p-error" id="text-error">
                            {{
                                (form.errors.password &&
                                    !form.password &&
                                    form.errors.password) ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>
                    <!-- END::Password section -->

                    <!-- BEGIN::Password Confirmation section -->
                    <div>
                        <span class="p-float-label">
                            <Password
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                class="w-full"
                                :class="
                                    form.errors.password_confirmation &&
                                    !form.password_confirmation &&
                                    'p-invalid'
                                "
                                toggle-mask
                                promptLabel="Choose a password"
                                weakLabel="Too simple"
                                mediumLabel="Average complexity"
                                strongLabel="Complex password"
                            >
                                <template #header>
                                    <h6>Pick a password</h6>
                                </template>
                                <template #footer>
                                    <Divider />
                                    <p class="mt-2">Suggestions</p>
                                    <ul
                                        class="pl-2 ml-2 mt-0"
                                        style="line-height: 1.5"
                                    >
                                        <li>At least one lowercase</li>
                                        <li>At least one uppercase</li>
                                        <li>At least one numeric</li>
                                        <li>Minimum 8 characters</li>
                                    </ul>
                                </template>
                            </Password>
                            <label for="password_confirmation">
                                Password Confirmation
                            </label>
                        </span>

                        <small class="p-error" id="text-error">
                            {{
                                (form.errors.password_confirmation &&
                                    form.errors.password_confirmation) ||
                                '&nbsp;'
                            }}
                        </small>
                    </div>
                    <!-- END::Password Confirmation section -->
                </div>

                <div class="grid grid-cols-1">
                    <div class="p-float-label">
                        <Dropdown
                            v-model="form.role"
                            :options="roles"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="Select a Role"
                            class="w-full md:w-14rem"
                            :class="form.errors.role && 'p-invalid'"
                        />
                        <label for="dd-city">Select a Role</label>
                    </div>

                    <small class="p-error" id="text-error">
                        {{
                            (form.errors.role &&
                                !form.role &&
                                form.errors.role) ||
                            '&nbsp;'
                        }}
                    </small>
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

<style>
.p-password-input {
    width: 100%;
}
</style>

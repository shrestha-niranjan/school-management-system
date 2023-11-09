<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useForm, usePage } from '@inertiajs/vue3'

defineProps({})

const schoolSetting = usePage().props.auth.schoolSetting

const form = useForm({
    name: schoolSetting.name,
    academic_year: schoolSetting.academic_year
})
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                School Information
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Update your schoool's basic informations.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('settings.update'))"
            v-focustrap
            class="mt-8 space-y-2"
        >
            <div>
                <span class="p-float-label">
                    <InputText
                        id="name"
                        autofocus
                        v-model="form.name"
                        class="w-full"
                        :class="form.errors.name && !form.name && 'p-invalid'"
                    />
                    <label for="name">School Name</label>
                </span>

                <small class="p-error" id="text-error">
                    {{
                        (form.errors.name && !form.name && form.errors.name) ||
                        '&nbsp;'
                    }}
                </small>
            </div>

            <div>
                <span class="p-float-label">
                    <InputNumber
                        v-model="form.academic_year"
                        :class="
                            form.errors.academic_year &&
                            !form.academic_year &&
                            'p-invalid'
                        "
                        :useGrouping="false"
                        id="academic_year"
                        class="w-full"
                        showButtons
                    />
                    <label for="academic_year">Academic Year</label>
                </span>

                <small class="p-error" id="text-error">
                    {{
                        (form.errors.academic_year &&
                            !form.academic_year &&
                            form.errors.academic_year) ||
                        '&nbsp;'
                    }}
                </small>
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

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({})

const schoolSetting = usePage().props.auth.schoolSetting

const form = useForm({
    name: schoolSetting.name,
    address: schoolSetting.address,
    established_at: schoolSetting.established_at,
    academic_year: schoolSetting.academic_year
})

const currentYear = new Date().getFullYear()

const academicYears = ref(generateYears(form.established_at, currentYear + 56))

function generateYears (start, end) {
    const years = []
    for (let year = start; year <= end; year++) {
        years.push(year)
    }
    return years
}
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

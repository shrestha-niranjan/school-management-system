<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import { Link, router, useForm } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import InputError from '@/Components/InputError.vue'
import BaseButton from '@/Components/BaseButton.vue'
import FormControl from '@/Components/FormControl.vue'
import SectionMain from '@/Components/SectionMain.vue'
import FormField from '@/Components/FormField.vue'
import { mdiArrowLeft, mdiStore } from '@mdi/js'

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
          toast('User updated successfully!', {
            autoClose: 1000,
            type: 'success'
          })
        }
      })
    : form.post(route('users.store'), {
        onSuccess: () => {
          toast('User created successfully!', {
            autoClose: 1000,
            type: 'success'
          })
        }
      })
}
</script>

<template>
  <SectionMain>
    <template #header>
      <div class="flex justify-between items-center rounded">
        <h1 class="text-lg font-semibold dark:text-white">  User/ {{ isEdit ? 'Edit' : 'Create' }}</h1>

        <BaseButton
          class="bg-red-600 text-white"
          label='Back'
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
            <v-select
              v-model="form.role"
              :options="roles"
              :reduce="role => role.label"
              label="label"
              value="id"
              class="dark:text-white"
              placeholder="Select Role"
            />
          </FormField>
          <InputError class="mt-2" :message="form.errors.role" />
        </div>

        <div class="flex justify-end">
          <BaseButton
            class="bg-sky-600 text-white"
            label='Save'
            :icon="mdiStore"
            type="submit"
          />
        </div>
      </form>
    </template>
  </SectionMain>
</template>

<style>

.vs__selected {
  @apply dark:text-white
}

.vs__dropdown-menu {
  @apply dark:bg-slate-800
}

.vs__dropdown-toggle {
  @apply border border-gray-500 dark:border-white w-full p-1  rounded shadow-sm
}
</style>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { mdiArrowLeft, mdiPencil, mdiPlus, mdiTrashCan } from '@mdi/js'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import Modal from '@/Components/Modal.vue'
import { toast } from 'vue3-toastify'
import 'vue3-toastify/dist/index.css'
import BasicTable from '@/Components/BasicTable.vue'
import BaseButton from '@/Components/BaseButton.vue'
import SectionMain from '@/Components/SectionMain.vue'
import BaseButtons from '@/Components/BaseButtons.vue'

defineOptions({
  layout: AuthenticatedLayout
})

defineProps({
  items: {
    type: Object
  },
  headers: Array
})

const openDeleteModal = ref(false)
const selectedItemId = ref()
const onDelete = () => {
  router.delete(route('users.destroy', {user: selectedItemId.value}), {
    onSuccess: () => {
      toast('User deleted successfully!', {
        autoClose: 3000,
        type: 'success'
      })
      selectedItemId.value = null
      openDeleteModal.value = false
    }
  })
}

const toggleDeleteModal = id => {
  id ? (selectedItemId.value = id) : ''
  openDeleteModal.value = !openDeleteModal.value
}

const fetchPaginatedData = (value) => {
  router.get(route('users.index'), {pagination_count: value})
}
</script>

<template>
  <SectionMain>
    <template #header>
      <div class="flex justify-between items-center rounded">
        <h1 class="text-lg font-semibold dark:text-white">Users</h1>
        <BaseButton
          class="bg-emerald-600 text-white"
          label='Create'
          :icon="mdiPlus"
          :routeName="route('users.create')"
        />
      </div>
    </template>
    <template #main>
      <BasicTable
        @update:pagination="fetchPaginatedData"
        :headers="['Name', 'Email', 'Role', 'Action']"
        :items="items"
        :actionWidth="'last:w-32'"
      >
        <template v-slot="{ item }">
          <td data-label="Name">{{ item.name }}</td>
          <td data-label="Email">{{ item.email }}</td>
          <td data-label="Role">{{ item.email }}</td>
          <td class="text-center w-20" data-label="Action">
            <BaseButtons class="flex max-w-fit">
              <BaseButton
                class="bg-sky-500 hover:bg-sky-600 text-white rounded"
                :routeName="route('users.edit', {user: item.id})"
                :icon="mdiPencil"
              />
              <BaseButton
                class="bg-red-500 hover:bg-red-600 text-white rounded"
                @click="toggleDeleteModal(item.id)"
                :icon="mdiTrashCan"
              />
            </BaseButtons>
          </td>
        </template>
      </BasicTable>

      <Modal v-model:show="openDeleteModal">
        <div class="h-44 flex flex-col justify-center px-6 gap-6">
          <p>Are you sure you want to delete?</p>
          <div class="flex justify-between gap-2">
            <BaseButton
              @click="toggleDeleteModal"
              class="bg-red-600 text-white hover:bg-red-700 duration-500"
              :icon="mdiArrowLeft"
              label="Back"
            />
            <BaseButton
              @click="onDelete"
              class="bg-sky-600 text-white hover:bg-sky-700 duration-500"
              :icon="mdiTrashCan"
              label="Delete"
            />
          </div>
        </div>
      </Modal>
    </template>
  </SectionMain>
</template>

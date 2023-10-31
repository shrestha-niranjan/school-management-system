<script setup>
import { computed, ref } from 'vue'
import TableCheckboxCell from '@/Components/TableCheckboxCell.vue'
import Pagination from '@/Components/Pagination.vue'
import FormControl from '@/Components/FormControl.vue'

const props = defineProps({
  checkable: Boolean,
  showAvatars: Boolean,
  headers: {
    type: Object,
    default: () => ({})
  },
  items: {
    type: Object,
    default: () => ({})
  },
  actionWidth: {
    type: String,
    default: 'md:last:w-32'
  }
})

const emit = defineEmits(['update:pagination'])

const checkedRows = ref([])

const remove = (arr, cb) => {
  const newArr = []

  arr.forEach(item => {
    if (!cb(item)) {
      newArr.push(item)
    }
  })

  return newArr
}

const checked = (isChecked, client) => {
  if (isChecked) {
    checkedRows.value.push(client)
  } else {
    checkedRows.value = remove(checkedRows.value, row => row.id === client.id)
  }
}

const pagination = ref({
  10: 10,
  20: 20,
  50: 50,
  100: 100
})

const selectedPagination = ref(props.items?.meta?.per_page ?? 20)

const updatePagination = (value) => {
  emit('update:pagination', value)
}
</script>

<template>
  <div v-if="checkedRows.length" class="p-3 bg-gray-100/50 dark:bg-slate-800">
    <span
      v-for="checkedRow in checkedRows"
      :key="checkedRow.id"
      class="inline-block px-2 py-1 rounded-sm mr-2 text-sm bg-gray-100 dark:bg-slate-700"
    >
      {{ checkedRow.name }}
    </span>
  </div>

  <table>
    <thead class="text-center bg-sky-500 text-white">
      <tr>
        <th v-if="checkable" />
        <th v-if="showAvatars" />
        <th>SN</th>
        <th v-for="header in headers" :class="actionWidth">
          {{ header }}
        </th>
      </tr>
    </thead>

    <tbody>
      <tr v-if="!items.data.length">
        <td class="text-center" :colspan="headers.length">
          No data available.
        </td>
      </tr>

      <tr v-for="(item, key) in items.data" :key="item.id">
        <TableCheckboxCell v-if="checkable" @checked="checked($event, item)" />

        <td data-label="SN" v-if="items.meta">
          <!-- {{ items.meta }} -->
          {{ (items.meta?.current_page - 1) * items.meta?.per_page + key + 1 }}
        </td>
        <td v-else data-label="SN">{{ key + 1 }}</td>
        <slot :item="item" />
      </tr>
    </tbody>
  </table>
  <div
    class="flex justify-between items-center mt-6"
    v-if="items.meta && items.meta.total > items.meta.per_page"
  >
    <div class="h-min w-min">
      <FormControl
        @update:model-value="updatePagination"
        placeholder="Select"
        class="w-20"
        :options="pagination"
        v-model="selectedPagination"
      />
    </div>
    <Pagination
      :links="items.meta.links"
      class="mt-6"
    />
  </div>
</template>

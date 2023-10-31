<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue'
import FormControlIcon from '@/Components/FormControlIcon.vue'

const props = defineProps({
  name: {
    type: String,
    default: null
  },
  id: {
    type: String,
    default: null
  },
  autocomplete: {
    type: String,
    default: null
  },
  maxlength: {
    type: String,
    default: null
  },
  placeholder: {
    type: String,
    default: null
  },
  inputmode: {
    type: String,
    default: null
  },
  icon: {
    type: String,
    default: null
  },
  options: {
    type: Object,
    default: null
  },
  type: {
    type: String,
    default: 'text'
  },
  error: {
    type: String,
    default: ''
  },
  modelValue: {
    type: [String, Number, Boolean, Array, Object],
    default: ''
  },
  isCustom: {
    type: Boolean,
    default: false
  },
  disabled: {
    type: Boolean,
    default: false
  },
  step: {
    type: Number,
    default: 0
  },

  required: Boolean,
  borderless: Boolean,
  transparent: Boolean,
  ctrlKFocus: Boolean
})

const emit = defineEmits(['update:modelValue', 'setRef'])

const computedValue = computed({
  get: () => props.modelValue,
  set: (value) => {
    emit('update:modelValue', value)
  }
})

const inputElClass = computed(() => {
  const base = [
    'px-3 py-2 max-w-full focus:ring-0  focus:outline-none border-gray-700 rounded w-full',
    'dark:placeholder-gray-400 dark:border-white dark:text-white',
    computedType.value === 'textarea' ? 'h-24' : 'h-10',
    props.borderless ? 'border-0' : 'border',
    props.transparent ? 'bg-transparent' : 'bg-white dark:bg-slate-800'
  ]

  if (props.icon) {
    base.push('pl-10')
  }

  return base
})

const computedType = computed(() => (props.options ? 'select' : props.type))

const controlIconH = computed(() =>
  props.type === 'textarea' ? 'h-full' : 'h-12'
)

const selectEl = ref(null)

const textareaEl = ref(null)

const inputEl = ref(null)

onMounted(() => {
  if (selectEl.value) {
    emit('setRef', selectEl.value)
  } else if (textareaEl.value) {
    emit('setRef', textareaEl.value)
  } else {
    emit('setRef', inputEl.value)
  }
})

if (props.ctrlKFocus) {
  const fieldFocusHook = (e) => {
    if (e.ctrlKey && e.key === 'k') {
      e.preventDefault()
      inputEl.value.focus()
    } else if (e.key === 'Escape') {
      inputEl.value.blur()
    }
  }
}
</script>

<template>
  <div class="relative">
    <select
      v-if="computedType === 'select'"
      :id="id"
      v-model="computedValue"
      :name="name"
      :class="[inputElClass, { 'border border-red-700': error }]"
      :disabled="disabled"
    >
      <option value="">{{ placeholder }}</option>
      <option
        v-if="!isCustom"
        v-for="(option, index) in options"
        :key="index"
        :value="index"
      >
        {{ option.label ?? option }}
      </option>
      <slot v-if="isCustom"></slot>
    </select>
    <textarea
      v-else-if="computedType === 'textarea'"
      :id="id"
      v-model="computedValue"
      :class="[inputElClass, { 'broder border-red-700': error }]"
      :name="name"
      :maxlength="maxlength"
      :placeholder="placeholder"
      :required="required"
    />
    <input
      v-else
      :id="id"
      ref="inputEl"
      v-model="computedValue"
      :name="name"
      :maxlength="maxlength"
      :inputmode="inputmode"
      :autocomplete="autocomplete"
      :required="required"
      :placeholder="placeholder"
      :type="computedType"
      :class="[inputElClass, { 'broder border-red-700': error }]"
      :step="step"
      :disabled="disabled"
    />
    <FormControlIcon
      v-if="icon"
      :icon="icon"
      :h="controlIconH"
    />
    <span
      class="text-xs py-1 text-red-700"
      v-if="error"
    >{{ error }}</span
    >
  </div>
</template>

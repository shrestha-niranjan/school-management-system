<script setup>
import { use } from 'echarts/core'
import { CanvasRenderer } from 'echarts/renderers'
import { BarChart, PieChart } from 'echarts/charts'
import {
  TitleComponent,
  TooltipComponent,
  LegendComponent,
  GridComponent
} from 'echarts/components'
import VChart, { THEME_KEY } from 'vue-echarts'
import { computed, provide } from 'vue'
import { useStyleStore } from '@/Store/style.js'

const props = defineProps({
  option: {
    type: Object,
    required: true
  }
})

use([
  CanvasRenderer,
  PieChart,
  BarChart,
  GridComponent,
  TitleComponent,
  TooltipComponent,
  LegendComponent
])

const styleStore = useStyleStore()

const theme = computed(() => {
  return styleStore.darkMode === false ? 'dark' : 'white'
})

provide(THEME_KEY, theme)

</script>
<template>
  <v-chart
    v-if="option"
    class="h-96 rounded-2xl p-0.5 " :option="option" autoresize/>
</template>

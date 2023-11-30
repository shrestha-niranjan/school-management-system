import './bootstrap'
import '../css/app.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m'
import { createPinia } from 'pinia'
import { useStyleStore } from '@/Store/style.js'
import PrimeVue from 'primevue/config'
import 'primevue/resources/themes/lara-light-teal/theme.css'
import 'primeicons/primeicons.css'
import Toast from 'primevue/toast'

import ConfirmationService from 'primevue/confirmationservice'
import ToastService from 'primevue/toastservice'
import ConfirmDialog from 'primevue/confirmdialog'
import InputText from 'primevue/inputtext'
import InlineMessage from 'primevue/inlinemessage'
import Password from 'primevue/password'
import Breadcrumb from 'primevue/breadcrumb'
import Dropdown from 'primevue/dropdown'
import InputNumber from 'primevue/inputnumber'
import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup'
import Row from 'primevue/row'
import FocusTrap from 'primevue/focustrap'
import Tooltip from 'primevue/tooltip'
import Dialog from 'primevue/dialog'
import Calendar from 'primevue/calendar'
import Divider from 'primevue/divider'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'
const pinia = createPinia()

createInertiaApp({
    title: title => `${title} - ${appName}`,
    resolve: name =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue')
        ),
    setup ({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue, Ziggy)
            .use(PrimeVue, { ripple: true })
            .use(ToastService)
            .use(ConfirmationService)
            .component('Toast', Toast)
            .component('ConfirmDialog', ConfirmDialog)
            .component('InputText', InputText)
            .component('InlineMessage', InlineMessage)
            .component('Password', Password)
            .component('Breadcrumb', Breadcrumb)
            .component('Dropdown', Dropdown)
            .component('InputNumber', InputNumber)
            .component('Button', Button)
            .component('DataTable', DataTable)
            .component('Column', Column)
            .component('ColumnGroup', ColumnGroup)
            .component('Row', Row)
            .component('Dialog', Dialog)
            .component('Calendar', Calendar)
            .component('Divider', Divider)
            .directive('focustrap', FocusTrap)
            .directive('tooltip', Tooltip)
            .mount(el)
    },
    progress: {
        color: '#4B5563'
    }
})

if (
    (!localStorage['darkMode'] &&
        window.matchMedia('(prefers-color-scheme: dark)').matches) ||
    localStorage['darkMode'] === '1'
) {
    useStyleStore(pinia).setDarkMode(true)
}

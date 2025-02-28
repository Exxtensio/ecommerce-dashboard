import {createApp, h} from 'vue'
import {createInertiaApp, router} from '@inertiajs/vue3'
import * as Flowbite from 'flowbite-vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'
import VueClickAway from 'vue3-click-away'
import mitt from 'mitt'
import {createPinia} from 'pinia'
import piniaPersistedState from 'pinia-plugin-persistedstate'
import _ from 'underscore'
import i18n from "./i18n"
import axios from "axios"

import './bootstrap';

import {
    FwbButton,
    FwbSpinner,
    FwbSelect,
    FwbTimeline,
    FwbTimelineBody,
    FwbTimelineContent,
    FwbTimelineItem,
    FwbTimelinePoint,
    FwbTimelineTime,
    FwbTimelineTitle,
    FwbTable,
    FwbTableBody,
    FwbTableCell,
    FwbTableHead,
    FwbTableHeadCell,
    FwbTableRow,
    FwbCheckbox,
    FwbInput,
    FwbImg,
    FwbFileInput,
    FwbAvatar,
    FwbAvatarStack,
    FwbAvatarStackCounter,
    FwbTextarea,
    FwbModal,
    FwbBreadcrumb,
    FwbBreadcrumbItem,
    FwbAlert
} from 'flowbite-vue'

import { AgCharts } from 'ag-charts-vue3'
import VueJsonPretty from 'vue-json-pretty'
import VueStarRating from 'vue-star-rating'
import Multiselect from "vue-multiselect";
import VueDatePicker from '@vuepic/vue-datepicker'

import Search from '@/Shared/Search.vue'
import Breadcrumb from '@/Shared/Breadcrumb.vue'
import Database from '@/Shared/Database.vue'
import HelpText from '@/Shared/HelpText.vue'
import HelpValidation from '@/Shared/HelpValidation.vue'
import AddButton from '@/Shared/Buttons/AddButton.vue'
import ActionDropdown from '@/Shared/Dropdown/ActionDropdown.vue'
import TotalCardDropdown from '@/Shared/Dropdown/TotalCardDropdown.vue'
import HorizontalChartCardDropdown from '@/Shared/Dropdown/HorizontalChartCardDropdown.vue'
import PieChartCardDropdown from '@/Shared/Dropdown/PieChartCardDropdown.vue'
import SortDropdown from '@/Shared/Dropdown/SortDropdown.vue'
import TrashedDropdown from '@/Shared/Dropdown/TrashedDropdown.vue'
import ColumnDropdown from '@/Shared/Dropdown/ColumnDropdown.vue'
import FilterDropdown from '@/Shared/Dropdown/FilterDropdown.vue'
import CopyButton from '@/Shared/Buttons/CopyButton.vue'
import ApprovedModal from '@/Shared/Modal/ApprovedModal.vue'
import Pagination from '@/Shared/Pagination.vue'
import UserDropdown from '@/Shared/Dropdown/UserDropdown.vue'
import DirectionButton from '@/Shared/Buttons/DirectionButton.vue'
import Sidebar from '@/Shared/Sidebar.vue'
import Logo from '@/Shared/Logo.vue'
import ThemeTypeButton from '@/Shared/Buttons/ThemeTypeButton.vue'
import Notification from '@/Shared/Notification.vue'

import TotalCard from '@/Shared/Card/TotalCard.vue'
import SimpleHorizontalBarCard from '@/Shared/Card/SimpleHorizontalBarCard.vue'
import SimpleLineCard from '@/Shared/Card/SimpleLineCard.vue'
import SimplePieCard from '@/Shared/Card/SimplePieCard.vue'
import PieWithVariableRadiusCard from '@/Shared/Card/PieWithVariableRadiusCard.vue'
import SimpleDonutCard from '@/Shared/Card/SimpleDonutCard.vue'

import {useGlobalStore} from '@/stores/globalStore.js'
import {useResourceStore} from '@/stores/resourceStore.js'
import {createStore} from '@/stores/selectedStore.js'

import registeredFields from './fields.js'
import registeredFilters from './filters.js'
import registeredCards from './cards.js'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.vue', {eager: true})
        return pages[`./Pages/${name}.vue`]
    },
    title: title => title ? `${title} - SellExx CRM` : 'SellExx CRM',
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)});
        const config = app.config.globalProperties
        const pinia = createPinia()

        const port = document.querySelector('meta[name="websocket-port"]')?.getAttribute('content')
        const scheme = document.querySelector('meta[name="websocket-scheme"]')?.getAttribute('content')

        config.emitter = mitt()
        config.$_ = _
        config.$router = router
        config.$axios = axios
        window.Pusher = Pusher
        window.Echo = config.$echo = new Echo({
            broadcaster: 'reverb',
            key: 'sellexx',
            wsHost: document.querySelector('meta[name="websocket-host"]')?.getAttribute('content'),
            namespace: 'Exxtensio.EcommerceDashboard.Events',
            wsPort: scheme === 'http' ? port : null,
            wssPort: scheme === 'https' ? port : null,
            forceTLS: scheme === 'https',
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            enabledTransports: ['ws', 'wss'],
        })

        app.use(plugin)
        app.use(i18n)

        pinia.use(piniaPersistedState)
        app.use(pinia)

        app.use(VueClickAway)

        Object.keys(Flowbite).forEach(componentName => {
            app.component(componentName, Flowbite[componentName])
        });

        Object.entries(registeredFields).forEach(([name, component]) => {
            app.component(name, component)
        });

        Object.entries(registeredCards).forEach(([name, component]) => {
            app.component(name, component)
        });

        app.component('fwb-alert', FwbAlert)
        app.component('fwb-button', FwbButton)
        app.component('fwb-spinner', FwbSpinner)
        app.component('fwb-select', FwbSelect)
        app.component('fwb-timeline', FwbTimeline)
        app.component('fwb-timeline-body', FwbTimelineBody)
        app.component('fwb-timeline-content', FwbTimelineContent)
        app.component('fwb-timeline-item', FwbTimelineItem)
        app.component('fwb-timeline-point', FwbTimelinePoint)
        app.component('fwb-timeline-time', FwbTimelineTime)
        app.component('fwb-timeline-title', FwbTimelineTitle)
        app.component('fwb-table', FwbTable)
        app.component('fwb-table-body', FwbTableBody)
        app.component('fwb-table-cell', FwbTableCell)
        app.component('fwb-table-head', FwbTableHead)
        app.component('fwb-table-head-cell', FwbTableHeadCell)
        app.component('fwb-table-row', FwbTableRow)
        app.component('fwb-checkbox', FwbCheckbox)
        app.component('fwb-input', FwbInput)
        app.component('fwb-textarea', FwbTextarea)
        app.component('fwb-img', FwbImg)
        app.component('fwb-file-input', FwbFileInput)
        app.component('fwb-avatar', FwbAvatar)
        app.component('fwb-avatar-stack', FwbAvatarStack)
        app.component('fwb-avatar-stack-counter', FwbAvatarStackCounter)
        app.component('fwb-modal', FwbModal)
        app.component('fwb-breadcrumb', FwbBreadcrumb)
        app.component('fwb-breadcrumb-item', FwbBreadcrumbItem)

        app.component('ag-charts', AgCharts)
        app.component('multiselect', Multiselect)
        app.component('vue-json-pretty', VueJsonPretty)
        app.component('star-rating', VueStarRating)
        app.component('VueDatePicker', VueDatePicker)

        app.component('exx-breadcrumb', Breadcrumb)
        app.component('exx-help-text', HelpText)
        app.component('exx-help-validation', HelpValidation)
        app.component('exx-search', Search)
        app.component('exx-database', Database)
        app.component('exx-add-button', AddButton)
        app.component('exx-action-dropdown', ActionDropdown)
        app.component('exx-sort-dropdown', SortDropdown)
        app.component('exx-total-card-dropdown', TotalCardDropdown)
        app.component('exx-horizontal-chart-card-dropdown', HorizontalChartCardDropdown)
        app.component('exx-pie-chart-card-dropdown', PieChartCardDropdown)
        app.component('exx-trashed-dropdown', TrashedDropdown)
        app.component('exx-column-dropdown', ColumnDropdown)
        app.component('exx-filter-dropdown', FilterDropdown)
        app.component('exx-user-dropdown', UserDropdown)
        app.component('exx-copy-button', CopyButton)
        app.component('exx-direction-button', DirectionButton)
        app.component('exx-theme-type-button', ThemeTypeButton)
        app.component('exx-approved-modal', ApprovedModal)
        app.component('exx-pagination', Pagination)
        app.component('exx-sidebar', Sidebar)
        app.component('exx-logo', Logo)
        app.component('exx-notification', Notification)

        app.component('exx-total-card', TotalCard)
        app.component('exx-simple-horizontal-bar-card', SimpleHorizontalBarCard)
        app.component('exx-simple-line-card', SimpleLineCard)
        app.component('exx-simple-pie-card', SimplePieCard)
        app.component('exx-pie-with-variable-radius-card', PieWithVariableRadiusCard)
        app.component('exx-simple-donut-card', SimpleDonutCard)

        Object.entries(registeredFilters).forEach(([name, filter]) => {
            app.component(name, filter)
        });

        config.$globalStore = useGlobalStore()
        config.$resourceStore = useResourceStore()
        props.initialPage.props.appPrefixes.forEach(prefix => {
            const store = createStore(`sellexx_${prefix}`);
            config[`$${prefix}Store`] = store(pinia);
        })

        config.$copyText = (text) => {
            if (navigator.clipboard) return navigator.clipboard.writeText(text)

            const textArea = document.createElement('textarea')
            textArea.value = text

            textArea.style.top = '0'
            textArea.style.left = '0'
            textArea.style.position = 'fixed'

            document.body.appendChild(textArea)
            textArea.focus()
            textArea.select()

            try {
                document.execCommand('copy')
                document.body.removeChild(textArea)
                return Promise.resolve()
            } catch (err) {
                document.body.removeChild(textArea)
                return Promise.reject(err)
            }
        }

        app.mount(el)

        localStorage.setItem('sellexx_language', config.$page.props.appLanguage)
        config.$globalStore.verifyKey(config.$page.props.appKey, config.$page.props.appSecret)
    },
})

import '../css/app.css'
import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/vue3'
import * as Flowbite from 'flowbite-vue'
import axios from 'axios'
import VueClickAway from 'vue3-click-away'
import mitt from 'mitt'
import {createPinia} from 'pinia'
import piniaPersistedState from 'pinia-plugin-persistedstate'
import _ from 'underscore';
import {createI18n} from 'vue-i18n';

import en from '../locale/en.json'
import ru from '../locale/ru.json'
import ua from '../locale/ua.json'
import es from '../locale/es.json'

const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem('sellexx_language') || 'en',
    fallbackLocale: 'en',
    messages: {
        en: en,
        ru: ru,
        ua: ua,
        es: es,
    }
})

const emitter = mitt()

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

import VueJsonPretty from 'vue-json-pretty'
import VueStarRating from 'vue-star-rating'
import Multiselect from "vue-multiselect";

import Search from '@/Shared/Search.vue'
import Breadcrumb from '@/Shared/Breadcrumb.vue'
import Database from '@/Shared/Database.vue'
import HelpText from '@/Shared/HelpText.vue'
import HelpValidation from '@/Shared/HelpValidation.vue'
import AddButton from '@/Shared/Buttons/AddButton.vue'
import ActionDropdown from '@/Shared/Dropdown/ActionDropdown.vue'
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

import {useGlobalStore} from '@/stores/globalStore.js'
import {useResourceStore} from '@/stores/resourceStore.js'
import {createStore} from '@/stores/selectedStore.js'

import registeredFields from './fields.js'
import registeredFilters from './filters.js'

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token')
}

const html = document.getElementById('main')
if (!localStorage.getItem('sellexx_theme_type'))
    localStorage.setItem('sellexx_theme_type', window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')

if (!localStorage.getItem('sellexx_direction'))
    localStorage.setItem('sellexx_direction', window.getComputedStyle ? window.getComputedStyle(html, null).getPropertyValue('direction') : html.currentStyle.direction)

html.classList.add(localStorage.getItem('sellexx_theme_type'))
html.setAttribute('dir', localStorage.getItem('sellexx_direction'))

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

        config.emitter = emitter
        config.$_ = _

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

        app.component('multiselect', Multiselect)
        app.component('vue-json-pretty', VueJsonPretty)
        app.component('star-rating', VueStarRating)

        app.component('exx-breadcrumb', Breadcrumb)
        app.component('exx-help-text', HelpText)
        app.component('exx-help-validation', HelpValidation)
        app.component('exx-search', Search)
        app.component('exx-database', Database)
        app.component('exx-add-button', AddButton)
        app.component('exx-action-dropdown', ActionDropdown)
        app.component('exx-sort-dropdown', SortDropdown)
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

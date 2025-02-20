<template>
    <Head :title="title"/>
    <nav class="fixed top-0 z-40 w-full border-b theme-brand-bg theme-brand-border-color">
        <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start rtl:justify-end">
                    <button
                        @click="openSidebar = !openSidebar"
                        type="button"
                        class="w-10 h-10 text-xs flex items-center justify-center md:hidden focus:outline-none font-medium rounded-md"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg v-if="openSidebar" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                        <svg v-else class="size-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            />
                        </svg>
                    </button>
                    <exx-logo class="hidden md:flex"/>
                    <button @click="change" class="toggle-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5" :class="{'rotate-180': minimize}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center">
                    <!--<exx-direction-button/>-->
                    <exx-theme-type-button/>
                    <exx-user-dropdown/>
                </div>
            </div>
        </div>
    </nav>
    <exx-sidebar :class="{'!translate-x-0': openSidebar}"/>
    <div class="mt-16" :class="{'sm:ml-[4.4rem]': minimize, 'md:ml-64': !minimize}">
        <slot/>
        <exx-notification/>
    </div>
</template>

<script>
import {Head} from '@inertiajs/vue3'

export default {
    components: {
        Head
    },
    props: {
        title: {
            type: String,
            default: null
        }
    },
    data() {
        return {
            currentTime: new Date().toLocaleTimeString(),
            intervalId: null,
            openSidebar: false
        };
    },
    beforeDestroy() {
        if (this.intervalId) clearInterval(this.intervalId)
    },

    mounted() {
        document.addEventListener('click', this.preventClick, true)
        if (this.access.errors.length) {
            this.emitter.emit('notify', {
                position: 'top-center',
                type: 'danger',
                duration: 9999999,
                message: this.access.errors
            })
        }
    },
    beforeUnmount() {
        document.removeEventListener('click', this.preventClick, true);
    },
    methods: {
        preventClick(event) {
            if (this.access.errors.length) {
                event.stopPropagation()
                event.preventDefault()
            }
        },
        change() {
            this.$globalStore.changeMenuToggle()
        }
    },
    computed: {
        minimize() {
            return this.$globalStore.menuToggle === 'true'
        },
        access() {
            return this.$globalStore.getAccess()
        }
    }
}
</script>

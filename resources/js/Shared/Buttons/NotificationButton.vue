<template>
    <div v-if="count" class="flex items-center relative ms-3" v-click-away="onClickAway">
        <button @click.prevent="open = !open" type="button" class="w-10 h-10 text-xs flex items-center justify-center relative">
            <BellAlertIcon class="size-5"/>
            <span class="text-xs absolute right-0 top-0">{{ count }}</span>
        </button>
        <div :class="{'hidden': !open}" class="absolute min-w-[280px] right-0 top-[45px] z-50 !divide-y-0 theme-brand-dropdown">
            <div>
                <h6 class="px-4 pt-3 mb-1 small-head-label">{{ $t('Available updates') }}</h6>
                <ul class="px-2 pb-2 notifications" role="none">
                    <li v-for="item in releases" class="flex justify-between">
                        <span>{{ $t('Release') }} {{ item.version }}</span>
                        <a :href="item.url" target="_blank" class="btn-link">{{ $t('read') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import {
    BellAlertIcon
} from '@heroicons/vue/24/outline';
export default {
    components: {
        BellAlertIcon
    },
    data() {
        return {
            open: false
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
    },
    computed: {
        releases() {
            return JSON.parse(localStorage.getItem('sellexx_releases'))
        },
        count() {
            return this.releases.length
        }
    }
}
</script>

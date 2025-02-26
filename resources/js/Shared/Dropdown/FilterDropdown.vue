<template>
    <div v-if="list.length" class="flex items-center space-x-3 relative" v-click-away="onClickAway">
        <button
            @click="open = !open"
            class="w-full btn-default md:w-auto flex items-center justify-center py-2 px-4 text-sm focus:z-10 max-2xl:h-[38px]"
            type="button"
        >
            <FunnelIcon class="size-5 -ml-1 mr-2 max-2xl:mr-0"/>
            <span class="mr-2 max-2xl:hidden">{{ $t('Filters') }}</span>{{ count }}
            <ChevronDownIcon class="-mr-1 ml-2 w-3.5 h-3.5 max-2xl:ml-1"/>
        </button>
        <div class="z-10 min-w-80 absolute right-0 top-[43px] theme-brand-dropdown !divide-y-0 max-lg:right-auto max-lg:left-0" :class="{'hidden': !open}">
            <div class="px-3 pt-3 space-y-3">
                <component
                    v-for="i in list"
                    class="component edit-component"
                    :key="i.component"
                    :is="i.component"
                    :class="i.component"
                    :filter="i"
                />
            </div>
            <div class="py-3">
                <div class="flex justify-between items-baseline border-t theme-brand-border-color w-full px-3 pt-3">
                    <button @click="setFilters" class="text-xs font-semibold btn-green-link hover:underline">
                        {{ $t('Apply') }}
                    </button>
                    <button v-if="count" @click.prevent="setEmptyFilters()" class="text-xs font-semibold btn-link hover:underline">
                        {{ $t('Clear all') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import {
    ChevronDownIcon
} from '@heroicons/vue/24/solid';

import {
    FunnelIcon
} from '@heroicons/vue/24/outline';

export default {
    mixins: [Base],
    components: {
        ChevronDownIcon,
        FunnelIcon
    },
    data() {
        return {
            open: false,
            list: [],
        }
    },
    mounted() {
        this.list = this.$resourceStore.filters
    },
    methods: {
        onClickAway(event) {
            if(event.target.dataset.button !== 'clear')
                this.open = false
        },
        async setFilters() {
            await this.$axios.post(this.getResourceUrl('c'), {
                key: 'filters',
                value: this.list.filter(item => item.value !== null)
                    .map(item => ({
                        key: item.relation,
                        relation: item.component === 'select-filter' && !item.separatable,
                        value: item.value
                    })) || []
            })
            this.$router.visit(this.getResourceUrl(''))
        },
        async setEmptyFilters() {
            await this.$axios.post(this.getResourceUrl('c'), {
                key: 'filters',
                value: []
            })
            this.$router.visit(this.getResourceUrl(''))
        }
    },
    computed: {
        count() {
            return Object.keys(this.$page.props.resource.data.options.filters).length
                ? Object.keys(this.$page.props.resource.data.options.filters).length
                : null
        },
    }
}
</script>

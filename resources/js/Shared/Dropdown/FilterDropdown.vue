<template>
    <div v-if="list.length" class="flex items-center space-x-3 w-full md:w-auto relative" v-click-away="onClickAway">
        <button
            @click="open = !open"
            class="w-full btn-default md:w-auto flex items-center justify-center py-2 px-4 text-sm focus:z-10"
            type="button"
        >
            <FunnelIcon class="size-5 -ml-1 mr-2"/>
            Filters {{ count }}
            <ChevronDownIcon class="-mr-1 ml-2 w-3.5 h-3.5"/>
        </button>
        <div class="z-10 min-w-80 absolute right-0 top-[43px] theme-brand-dropdown !divide-y-0" :class="{'hidden': !open}">
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
                        Apply
                    </button>
                    <button v-if="count" @click.prevent="setEmptyFilters()" class="text-xs font-semibold btn-link hover:underline">
                        Clear all
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {router} from "@inertiajs/vue3";
import Base from "@/mixins/Base.js";
import {
    ChevronDownIcon,
    FunnelIcon
} from '@heroicons/vue/24/solid';

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
            await axios.post(this.getResourceUrl('c'), {
                key: 'filters',
                value: this.list.filter(item => item.value !== null)
                    .map(item => ({
                        key: item.relation,
                        relation: item.component === 'select-filter' && !item.separatable,
                        value: item.value
                    })) || []
            })
            router.visit(this.getResourceUrl(''))
        },
        async setEmptyFilters() {
            await axios.post(this.getResourceUrl('c'), {
                key: 'filters',
                value: []
            })
            router.visit(this.getResourceUrl(''))
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

<template>
    <div v-if="sortable.length" class="flex items-center space-x-3 relative" v-click-away="onClickAway">
        <button
            @click="open = !open"
            :class="[isDefault ? 'btn-default' : 'btn-success']"
            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm focus:z-10 max-2xl:h-[38px]"
        >
            <ArrowsUpDownIcon class="size-5 -ml-1 mr-2 max-2xl:mr-0"/>
            <span class="max-2xl:hidden">{{ $t('Sort By') }}</span>
            <ChevronDownIcon class="-mr-1 ml-2 w-3.5 h-3.5 max-2xl:ml-1"/>
        </button>
        <div class="z-10 min-w-56 absolute right-0 top-[43px] theme-brand-dropdown !divide-y-0 max-lg:right-auto max-lg:left-0" :class="{'hidden': !open}">
            <div class="px-3 pt-3">
                <h6 class="mb-2 small-head-label">{{ $t('Column') }}</h6>
                <ul class="space-y-2 mb-3">
                    <li class="flex items-center" v-for="item in sortable">
                        <input
                            :id="`sort-column-${item.attribute}`"
                            type="radio"
                            :value="item.attribute"
                            name="sorting-column"
                            :checked="options.sort === item.attribute"
                            v-model="sort"
                        >
                        <label
                            :for="`sort-column-${item.attribute}`"
                            class="ml-2 text-sm font-medium select-none"
                        >
                            {{ item.name }}
                        </label>
                    </li>
                </ul>
                <h6 class="mb-2 small-head-label">{{ $t('Sorting') }}</h6>
                <ul class="space-y-2">
                    <li class="flex items-center" v-for="type in ['asc', 'desc']">
                        <input
                            :id="`sort-column-${type}`"
                            type="radio"
                            :value="type"
                            name="sorting-type"
                            :checked="options.order === type"
                            v-model="order"
                        >
                        <label :for="`sort-column-${type}`" class="ml-2 text-sm font-medium select-none">
                            {{ type === 'asc' ? $t('Ascending') : $t('Descending') }}
                        </label>
                    </li>
                </ul>
            </div>
            <div class="py-3">
                <div class="flex justify-between items-baseline border-t theme-brand-border-color w-full px-3 pt-3">
                    <button @click.prevent="setSort(sort, order)" class="text-xs font-semibold btn-green-link hover:underline">
                        {{ $t('Apply') }}
                    </button>
                    <button v-if="!isDefault" @click.prevent="setSort('id', 'asc')" class="text-xs font-semibold btn-link hover:underline">
                        {{ $t('Default') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Base from "@/mixins/Base.js";
import {
    ChevronDownIcon,
    ArrowsUpDownIcon
} from '@heroicons/vue/24/solid';

export default {
    mixins: [Base],
    components: {
        ChevronDownIcon,
        ArrowsUpDownIcon
    },
    data() {
        return {
            open: false,
            sort: this.$page.props.resource.data.options.sort,
            order: this.$page.props.resource.data.options.order
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
        async cacheRequest(name, value) {
            await this.$axios.post(this.getResourceUrl('c'), {
                key: name,
                value: value,
            })
        },
        async setSort(sort, order) {
            await this.cacheRequest('sort', sort)
            await this.cacheRequest('order', order)

            this.$router.visit(this.getResourceUrl(''))
        },
    },
    computed: {
        options() {
            return this.$page.props.resource.data.options
        },
        isDefault() {
            return this.options.sort === 'id' && this.options.order === 'asc'
        },
        sortable() {
            return this.$resourceStore.data.length ? this.$resourceStore.data[0].filter(c => c.sortable) : []
        },
    }
}
</script>

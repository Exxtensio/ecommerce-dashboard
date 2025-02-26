<template>
    <div v-if="list.length" class="flex items-center space-x-3 relative" v-click-away="onClickAway">
        <button
            @click="open = !open"
            class="w-full btn-default md:w-auto flex items-center justify-center py-2 px-4 text-sm focus:z-10 max-2xl:h-[38px]"
            type="button"
        >
            <AdjustmentsVerticalIcon class="size-5 -ml-1 mr-2 max-2xl:mr-0"/>
            <span class="max-2xl:hidden">{{ $t('Columns') }}</span>
            <ChevronDownIcon class="-mr-1 ml-2 w-3.5 h-3.5 max-2xl:ml-1"/>
        </button>
        <div class="z-10 min-w-56 absolute right-0 top-[43px] theme-brand-dropdown !divide-y-0 max-lg:right-auto max-lg:left-0" :class="{'hidden': !open}">
            <div class="px-3 pt-3">
                <ul class="space-y-2">
                    <li
                        class="flex items-center"
                        v-for="item in list"
                        :class="{
                            'opacity-40': item.attribute === 'id' || item.attribute === 'deleted_at' || item.attribute === 'event'
                        }"
                    >
                        <input
                            :id="`column-${item.attribute}`"
                            type="checkbox"
                            :value="item.attribute"
                            :disabled="item.attribute === 'id' || item.attribute === 'deleted_at' || item.attribute === 'event'"
                            name="columns"
                            v-model="item.showOnIndex"
                        >
                        <label
                            :for="`column-${item.attribute}`"
                            class="ml-2 text-sm font-medium select-none"
                        >
                            {{ item.name }}
                        </label>
                    </li>
                </ul>
            </div>
            <div class="py-3">
                <div class="flex justify-between items-baseline border-t theme-brand-border-color w-full px-3 pt-3">
                    <button @click="setColumns" class="text-xs font-semibold btn-green-link hover:underline">
                        {{ $t('Apply') }}
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
    AdjustmentsVerticalIcon
} from '@heroicons/vue/24/outline';

export default {
    mixins: [Base],
    components: {
        ChevronDownIcon,
        AdjustmentsVerticalIcon
    },
    data() {
        return {
            open: false,
            singleRelations: ['belong-to-field', 'morph-to-field'],
            list: [],
        }
    },
    mounted() {
        if (this.$resourceStore.data.length) {
            this.list = this.$resourceStore.data[0]
                .filter(c => c.showOnColumns)
                .map(item => {
                    return {
                        component: item.component,
                        relation: item.attribute,
                        attribute: this.singleRelations.includes(item.component) ? item.foreignKey : item.attribute,
                        morphType: item.morphType || null,
                        name: item.name,
                        showOnIndex: item.showOnIndex,
                        toColumns: !item.relationName,
                        toRelations: !!item.relationName,
                    }
                })
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
        async setColumns() {
            await this.$axios.post(this.getResourceUrl('c'), {
                key: 'columns',
                value: this.list
                    .filter(c => c.showOnIndex && (c.toColumns || (c.toRelations && this.singleRelations.includes(c.component))))
                    .reduce((acc, c) => acc.concat(c.attribute, c.morphType), [])
                    .filter(Boolean),
                relations: this.list
                    .filter(c => c.showOnIndex && c.toRelations)
                    .reduce((acc, c) => acc.concat(c.relation), [])
                    .filter(Boolean),
            })
            this.$router.visit(this.getResourceUrl(''))
        }
    }
}
</script>

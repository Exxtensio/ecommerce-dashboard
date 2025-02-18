<template>
    <div v-if="hasSoftDelete" class="flex items-center space-x-3 w-full md:w-auto relative" v-click-away="onClickAway">
        <button
            @click="open = !open"
            :class="[isDefault ? 'btn-default' : 'btn-success']"
            class="w-full md:w-auto flex items-center justify-center py-2 px-4 text-sm focus:z-10"
        >
            <TrashIcon class="size-4 -ml-1 mr-2"/>
            Trashed
            <ChevronDownIcon class="-mr-1 ml-2 w-3.5 h-3.5"/>
        </button>
        <div class="z-10 min-w-44 absolute right-0 top-[43px] theme-brand-dropdown !divide-y-0" :class="{'hidden': !open}">
            <div class="px-3 pt-3">
                <h6 class="mb-2 small-head-label">Options</h6>
                <ul class="space-y-2">
                    <li class="flex items-center" v-for="item in ['without','with','only']">
                        <input
                            :id="`trashed-column-${item}`"
                            type="radio"
                            :value="item"
                            name="trashed-column"
                            :checked="options.trashed === item"
                            v-model="trashed"
                        >
                        <label
                            :for="`trashed-column-${item}`"
                            class="ml-2 text-sm font-medium select-none"
                        >
                            {{ capitalize(item) }}
                        </label>
                    </li>
                </ul>
            </div>
            <div class="py-3">
                <div class="flex justify-between items-baseline border-t theme-brand-border-color w-full px-3 pt-3">
                    <button @click.prevent="setTrashed(trashed)" class="text-xs font-semibold btn-green-link hover:underline">
                        Apply
                    </button>
                    <button v-if="!isDefault" @click.prevent="setTrashed('without')" class="text-xs font-semibold btn-link hover:underline">
                        Default
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
    TrashIcon
} from '@heroicons/vue/24/solid';

export default {
    mixins: [Base],
    components: {
        ChevronDownIcon,
        TrashIcon
    },
    data() {
        return {
            open: false,
            trashed: this.$page.props.resource.data.options.trashed
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
        async setTrashed(trashed) {
            await axios.post(this.getResourceUrl('c'), {
                key: 'trashed',
                value: trashed,
            })
            router.visit(this.getResourceUrl(''))
        },
        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        }
    },
    computed: {
        options() {
            return this.$page.props.resource.data.options
        },
        isDefault() {
            return this.options.trashed === 'without'
        }
    }
}
</script>

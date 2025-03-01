<template>
    <div class="w-full h-full relative">
        <button class="absolute w-[16px] -top-[16px] -right-[16px]">
            <TrashIcon class="size:4 text-[var(--exx-danger-color)]" @click="$emit('deleteCard')"/>
        </button>
        <p class="text-sm mb-1">{{ response.title }}</p>
        <span class="text-xs flex gap-2 items-center justify-start text-gray-400 dark:text-gray-500">
            <img class="w-4" :src="response.country.flag" :alt="response.country.code">
            <span>{{ response.country.name }} (by {{response.by}})</span>
        </span>
        <Transition name="fade">
            <div v-if="show" class="scrollable-grid grid w-full h-full overflow-x-auto">
                <ag-charts :options="options"/>
            </div>
        </Transition>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import {TrashIcon} from "@heroicons/vue/24/outline/index.js";

export default {
    components: {TrashIcon},
    mixins: [Base],
    props: {
        response: {
            type: Object,
            required: true,
        }
    },
    data() {
        return {
            show: false,
            options: {
                series: [
                    {
                        type: 'bar',
                        direction: 'horizontal',
                        xKey: 'key',
                        yKey: 'value',
                        cornerRadius: 4,
                        label: {
                            formatter: ({value}) => `${value.toFixed(0)}`,
                        },
                        itemStyler: ({datum, yKey}) => ({
                            fillOpacity: this.getOpacity(datum[yKey], yKey, 0.4, 1),
                        }),
                    },
                ],
                axes: [
                    {
                        type: 'category',
                        position: 'left',
                    },
                    {
                        type: 'number',
                        position: 'bottom',
                        label: {formatter: ({value}) => `${value.toFixed(0)}`},
                    },
                ],
            }
        }
    },
    created() {
        this.options.data = this.response.data
        this.options.theme = this.theme === 'dark' ? 'ag-default-dark' : 'ag-default'
        if (this.theme === 'dark') this.options.background = {fill: '#132337'}
    },
    methods: {
        map(value, start1, end1, start2, end2) {
            return ((value - start1) / (end1 - start1)) * (end2 - start2) + start2;
        },
        getDomain(key) {
            const min = Math.min(...this.options.data.map((d) => d[key]));
            const max = Math.max(...this.options.data.map((d) => d[key]));
            return [min, max];
        },
        getOpacity(value, key, minOpacity, maxOpacity) {
            const [min, max] = this.getDomain(key);
            let alpha = Math.round(((value - min) / (max - min)) * 10) / 10;
            return this.map(alpha, 0, 1, minOpacity, maxOpacity);
        }
    },
    mounted() {
        this.emitter.on('themeChanged', () => {
            this.show = false
        })
        this.show = true
    }
}
</script>

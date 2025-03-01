<template>
    <div class="w-full h-full relative">
        <button v-if="canDelete" class="absolute w-[16px] -top-[16px] -right-[16px]">
            <TrashIcon class="size:4 text-[var(--exx-danger-color)]" @click="$emit('deleteCard')"/>
        </button>
        <p class="text-sm mb-1">{{ response.title }}</p>
        <span class="text-xs flex gap-2 items-center justify-start text-gray-400 dark:text-gray-500">
            <img class="w-4" :src="response.country.flag" :alt="response.country.code">
            <span>{{ response.country.name }} (by {{ response.by }})</span>
        </span>
        <Transition name="fade">
            <div v-if="show" class="scrollable-grid grid w-full h-full overflow-x-auto">
                <ag-charts :options="options"/>
            </div>
        </Transition>
    </div>
</template>

<script>
import Base from '@/mixins/Base.js';
import {TrashIcon} from '@heroicons/vue/24/outline/index.js';
const numFormatter = new Intl.NumberFormat('en-US');

export default {
    components: {TrashIcon},
    mixins: [Base],
    props: {
        response: {
            type: Object,
            required: true,
        },
        canDelete: {
            type: Boolean,
            required: true,
        }
    },
    data() {
        return {
            show: false,
            options: {
                series: [
                    {
                        type: 'pie',
                        calloutLabelKey: 'name',
                        sectorLabelKey: 'value',
                        angleKey: 'value',
                        calloutLabel: {
                            offset: 10,
                        },
                        sectorLabel: {
                            formatter: ({datum, sectorLabelKey = 'value'}) => {
                                return `${numFormatter.format(datum[sectorLabelKey])}`;
                            },
                        },
                        tooltip: {
                            renderer: ({datum, angleKey, calloutLabelKey = 'name'}) => ({
                                data: [
                                    {
                                        label: `${datum[calloutLabelKey]}`,
                                        value: `${datum[angleKey]}`,
                                    },
                                ],
                            }),
                        }
                    },
                ],
                legend: {
                    enabled: false
                },
            }
        }
    },
    created() {
        this.options.series[0].data = this.response.data
        this.options.theme = this.theme === 'dark' ? 'ag-default-dark' : 'ag-default'
        if (this.theme === 'dark') this.options.background = {fill: '#132337'}
    },
    mounted() {
        this.emitter.on('themeChanged', () => {
            this.show = false
        })
        this.show = true
    }
}
</script>

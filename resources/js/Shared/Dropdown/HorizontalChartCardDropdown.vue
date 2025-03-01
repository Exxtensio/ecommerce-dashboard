<template>
    <div class="flex items-center space-x-3 relative w-full h-full" v-click-away="onClickAway">
        <button @click="open = !open" class="absolute flex justify-center items-center left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full cursor-pointer">
            <PlusIcon class="size-4"/>
        </button>
        <div class="z-10 min-w-[300px] absolute left-1/2 top-0 theme-brand-dropdown !divide-y-0 -translate-x-1/2" :class="{'hidden': !open}">
            <div class="component edit-component text-filter px-3 pt-3">
                <h6 class="mb-2 small-head-label">{{ $t('Title') }}</h6>
                <fwb-input
                    v-model="title"
                    size="sm"
                    :validation-status="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('title') ? 'error' : null"
                >
                    <template v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('title')" #validationMessage>
                        <exx-help-validation :text="errors[`${type}_${position}`].title[0]"/>
                    </template>
                </fwb-input>
                <div class="component edit-component select-filter mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Chart (chart)') }}</h6>
                    <multiselect
                        v-model="chart"
                        :options="$page.props.options.horizontalCharts"
                        :close-on-select="true"
                        :allow-empty="false"
                        :show-labels="true"
                        class="w-full"
                        track-by="value"
                        label="name"
                    >
                        <template #caret>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </template>
                    </multiselect>
                    <exx-help-validation
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('chart')"
                        :text="errors[`${type}_${position}`].chart[0]"
                        type="text"
                    />
                </div>
                <div class="component edit-component select-filter mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Country (value)') }}</h6>
                    <multiselect
                        v-model="value"
                        :options="$page.props.options.countries"
                        :close-on-select="true"
                        :allow-empty="false"
                        :show-labels="true"
                        class="w-full"
                        track-by="value"
                        label="name"
                    >
                        <template #caret>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </template>
                    </multiselect>
                    <exx-help-validation
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('value')"
                        :text="errors[`${type}_${position}`].value[0]"
                        type="text"
                    />
                </div>
                <div class="component edit-component select-filter mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Order Status (column)') }}</h6>
                    <multiselect
                        v-model="column"
                        :options="$page.props.options.orderStatuses"
                        :close-on-select="true"
                        :allow-empty="false"
                        :show-labels="true"
                        class="w-full"
                        track-by="value"
                        label="name"
                    >
                        <template #caret>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </template>
                    </multiselect>
                    <exx-help-validation
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('column')"
                        :text="errors[`${type}_${position}`].column[0]"
                        type="text"
                    />
                </div>
                <div class="component edit-component select-filter mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('X Axis (x_axis)') }}</h6>
                    <multiselect
                        v-model="x_axis"
                        :options="$page.props.options.xAxis"
                        :close-on-select="true"
                        :allow-empty="false"
                        :show-labels="true"
                        class="w-full"
                        track-by="value"
                        label="name"
                    >
                        <template #caret>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </template>
                    </multiselect>
                    <exx-help-validation
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('x_axis')"
                        :text="errors[`${type}_${position}`].x_axis[0]"
                        type="text"
                    />
                </div>
                <div class="component edit-component select-filter mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Y Axis (y_axis)') }}</h6>
                    <multiselect
                        v-model="y_axis"
                        :options="$page.props.options.yAxis"
                        :close-on-select="true"
                        :allow-empty="false"
                        :show-labels="true"
                        class="w-full"
                        track-by="value"
                        label="name"
                    >
                        <template #caret>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </template>
                    </multiselect>
                    <exx-help-validation
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('y_axis')"
                        :text="errors[`${type}_${position}`].y_axis[0]"
                        type="text"
                    />
                </div>
                <div v-if="y_axis && y_axis.value === 'attributes'" class="component edit-component select-filter mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Y Axis Value (y_axis_key)') }}</h6>
                    <multiselect
                        v-model="y_axis_key"
                        :options="$page.props.options.yAxisKeys"
                        :close-on-select="true"
                        :allow-empty="false"
                        :show-labels="true"
                        class="w-full"
                        track-by="value"
                        label="name"
                    >
                        <template #caret>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                 class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </template>
                    </multiselect>
                    <exx-help-validation
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('y_axis_key')"
                        :text="errors[`${type}_${position}`].y_axis_key[0]"
                        type="text"
                    />
                </div>
            </div>
            <div class="py-3">
                <div class="flex justify-between items-baseline border-t theme-brand-border-color w-full px-3 pt-3">
                    <button
                        @click="save"
                        class="text-xs font-semibold btn-green-link hover:underline"
                    >
                        {{ $t('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import Base from "@/mixins/Base.js";
import {
    PlusIcon
} from '@heroicons/vue/24/solid';

export default {
    mixins: [Base],
    emits: ['saveCard'],
    props: {
        type: {
            type: String,
            required: true
        },
        position: {
            type: Number,
            required: true
        },
        errors: {
            type: Object,
            default: {}
        }
    },
    components: {
        PlusIcon
    },
    data() {
        return {
            open: false,
            chart: null,
            model: 'orders',
            title: null,
            value: null,
            column: null,
            x_axis: null,
            y_axis: null,
            y_axis_key: null
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
        save() {
            this.$emit('saveCard', {
                title: this.title,
                model: this.model,
                chart: this.chart?.value ?? null,
                value: this.value?.value ?? null,
                column: this.column?.value ?? null,
                x_axis: this.x_axis?.value ?? null,
                y_axis: this.y_axis?.value ?? null,
                y_axis_key: this.y_axis_key?.value ?? null,
                type: this.type,
                position: this.position,
            })
        }
    }
}
</script>

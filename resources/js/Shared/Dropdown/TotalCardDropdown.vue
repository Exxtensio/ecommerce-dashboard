<template>
    <div class="flex items-center space-x-3 relative w-full h-full" v-click-away="onClickAway">
        <button @click="open = !open" class="absolute flex justify-center items-center left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full cursor-pointer">
            <PlusIcon class="size-4"/>
        </button>
        <div class="z-10 min-w-[300px] absolute left-1/2 top-0 theme-brand-dropdown !divide-y-0 -translate-x-1/2" :class="{'hidden': !open}">
            <div class="component edit-component select-filter px-3 pt-3">
                <h6 class="mb-2 small-head-label">{{ $t('Model') }}</h6>
                <multiselect
                    v-model="model"
                    :options="modelOptions"
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
                    v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('model')"
                    :text="errors[`${type}_${position}`].model[0]"
                    type="text"
                />
                <div v-if="model && needle" class="mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t(valueLabel) }}</h6>
                    <multiselect
                        v-model="value"
                        :options="valueOptions"
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
                <div v-if="value && model && model.value === 'orders'" class="mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Column (column)') }}</h6>
                    <multiselect
                        v-model="column"
                        :options="$page.props.options.orderTypes"
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
                <div v-if="column && model && model.value === 'orders'" class="mt-3">
                    <h6 class="mb-2 small-head-label">{{ $t('Column Value (column_value)') }}</h6>
                    <multiselect
                        v-model="columnValue"
                        :options="column.value === 'status' ? $page.props.options.orderStatuses : $page.props.options.orderPaymentStatuses"
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
                        v-if="errors.hasOwnProperty(`${type}_${position}`) && errors[`${type}_${position}`].hasOwnProperty('column_value')"
                        :text="errors[`${type}_${position}`].column_value[0]"
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
            model: null,
            value: null,
            column: null,
            columnValue: null,
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
        save() {
            this.$emit('saveCard', {
                model: this.model?.value ?? null,
                value: this.value?.value ?? null,
                column: this.column?.value ?? null,
                columnValue: this.columnValue?.value ?? null,
                type: this.type,
                position: this.position,
            })
        }
    },
    watch: {
        model(newValue, oldValue) {
            this.value = null
            this.column = null
        }
    },
    computed: {
        modelOptions() {
            const values = ['users', 'brands', 'categories', 'attributes', 'products', 'reviews', 'carts', 'orders']
            return values.map(value => ({
                name: value.charAt(0).toUpperCase() + value.slice(1),
                value
            }))
        },
        valueLabel() {
            const map = {
                users: 'Role (value)',
                products: 'Status (value)',
                reviews: 'Rating (value)',
                carts: 'Country (value)',
                orders: 'Country (value)'
            }
            return map[this.model?.value] || 'Value'
        },
        valueOptions() {
            const map = {
                users: this.$page.props.options.roles,
                products: this.$page.props.options.productStatuses,
                reviews: this.$page.props.options.ratings,
                carts: this.$page.props.options.countries,
                orders: this.$page.props.options.countries
            }
            return map[this.model?.value] || []
        },
        needle() {
            return this.model && ['users', 'products', 'reviews', 'carts', 'orders'].includes(this.model.value)
        },
    }
}
</script>

<template>
    <div :class="{'required': isRequired}">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div class="grid gap-4 grid-cols-2 rounded-md border theme-brand-border-color p-4">
            <div v-for="inventory in field.resolvedForUpdate" class="rounded-md border theme-brand-border-color p-4">
                <label class="head-label flex gap-3 items-center justify-start mb-4">
                    <img class="w-5" v-if="countrySVG(inventory.country)" :src="countrySVG(inventory.country)" :alt="inventory.country"/>
                    <span>{{ countryName(inventory.country) }}</span>
                </label>
                <div class="space-y-3">
                    <div class="number-field edit-component xs">
                        <fwb-input
                            ref="input"
                            v-model="inventory.price"
                            class="input"
                            label="Price"
                            size="sm"
                            @input="validateLazyInput(inventory.country, 'price', $event)"
                        >
                            <template #prefix>
                                <fwb-button
                                    class="btn-light p-3 relative !size-[40px] !rounded-tr-none !rounded-br-none flex justify-center items-center"
                                    color="light"
                                    size="sm"
                                    @click="decrease(inventory.country, 'price')"
                                    square
                                >
                                    <MinusIcon class="size-3 font-medium"/>
                                </fwb-button>
                            </template>
                            <template #suffix>
                                <fwb-button
                                    class="btn-light p-3 relative !size-[40px] !rounded-tl-none !rounded-bl-none flex justify-center items-center"
                                    color="light"
                                    size="xs"
                                    @click="increase(inventory.country, 'price')"
                                    square
                                >
                                    <PlusIcon class="size-3 font-medium"/>
                                </fwb-button>
                            </template>
                        </fwb-input>
                    </div>
                    <div class="number-field edit-component xs">
                        <fwb-input
                            ref="input"
                            v-model="inventory.stock"
                            class="input"
                            label="Stock"
                            size="sm"
                            @input="validateLazyInput(inventory.country, 'stock', $event)"
                        >
                            <template #prefix>
                                <fwb-button
                                    class="btn-light p-3 relative !size-[40px] !rounded-tr-none !rounded-br-none flex justify-center items-center"
                                    color="light"
                                    size="xs"
                                    @click="decrease(inventory.country, 'stock')"
                                    square
                                >
                                    <MinusIcon class="size-3 font-medium"/>
                                </fwb-button>
                            </template>
                            <template #suffix>
                                <fwb-button
                                    class="btn-light p-3 relative !size-[40px] !rounded-tl-none !rounded-bl-none flex justify-center items-center"
                                    color="light"
                                    size="xs"
                                    @click="increase(inventory.country, 'stock')"
                                    square
                                >
                                    <PlusIcon class="size-3 font-medium"/>
                                </fwb-button>
                            </template>
                        </fwb-input>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Base from '@/mixins/Base.js'
import {
    MinusIcon,
    PlusIcon
} from '@heroicons/vue/24/solid'

export default {
    mixins: [Base],
    components: {
        MinusIcon,
        PlusIcon
    },
    props: {
        field: {
            type: Object,
            required: true
        },
        errors: {
            type: Object,
            required: true
        }
    },
    created() {
        if (!this.field.resolvedForUpdate) this.field.resolvedForUpdate = []

        const priceStockMap = this.field.resolvedForUpdate.reduce((map, item) => {
            map[item.country] = item;
            return map;
        }, {}) || [];

        const updatedPriceStock = this.relations.countries.filter(c => c.active).map(activeCountry => {
            const existingEntry = priceStockMap[activeCountry.code];
            return existingEntry || {
                country: activeCountry.code,
                stock: 0,
                price: 0
            };
        });

        this.field.resolvedForUpdate = updatedPriceStock.map(item => ({
            ...item,
            price: parseFloat(item.price).toFixed(2),
            stock: parseFloat(item.stock).toFixed(2)
        }))
    },
    methods: {
        country(code) {
            return this.relations.hasOwnProperty('countries') ? this.relations.countries.find(c => c.code === code) : null
        },
        countryName(code) {
            if (!this.country(code)) return code
            return this.country(code).name
        },
        countrySVG(code) {
            return this.country(code) ? this.country(code).flag : null
        },
        validateLazyInput(country, type, e) {
            let sanitizedValue = this.field.resolvedForUpdate.find(c => c.country === country)[type]
            sanitizedValue = typeof this.field.resolvedForUpdate.find(c => c.country === country)[type] !== 'string'
                ? this.field.resolvedForUpdate.find(c => c.country === country)[type].toString().replace(/[^0-9.]/g, '')
                : this.field.resolvedForUpdate.find(c => c.country === country)[type].replace(/[^0-9.]/g, '')


            const parts = sanitizedValue.split('.')

            if (parts.length === 2 && parts[1] > 2) {
                this.field.resolvedForUpdate.find(c => c.country === country)[type] = parts[0] + '.' + parts[1].slice(0, 2);
            } else {
                this.field.resolvedForUpdate.find(c => c.country === country)[type] = sanitizedValue;
            }

            this.field.resolvedForUpdate.find(c => c.country === country)[type] = parseFloat(this.field.resolvedForUpdate.find(c => c.country === country)[type]).toFixed(2)

            this.limiterMax(country, type)
            this.limiterMin(country, type)

            if(!e.target.value) {
                this.field.resolvedForUpdate.find(c => c.country === country)[type] = parseFloat('0').toFixed(2)
                e.target.value = this.field.resolvedForUpdate.find(c => c.country === country)[type]
            }
        },
        limiterMin(country, type) {
            if (parseFloat(this.field.resolvedForUpdate.find(c => c.country === country)[type]) < 0)
                this.field.resolvedForUpdate.find(c => c.country === country)[type] = parseFloat('0').toFixed(2)
        },
        limiterMax(country, type) {
            if (parseFloat(this.field.resolvedForUpdate.find(c => c.country === country)[type]) > 99999999.99)
                this.field.resolvedForUpdate.find(c => c.country === country)[type] = parseFloat('99999999.99').toFixed(2)
        },
        increase(country, type) {
            this.field.resolvedForUpdate.find(c => c.country === country)[type] = (parseFloat(this.field.resolvedForUpdate.find(c => c.country === country)[type]) + 0.10).toFixed(2)
            this.limiterMax(country, type)
        },
        decrease(country, type) {
            this.field.resolvedForUpdate.find(c => c.country === country)[type] = (parseFloat(this.field.resolvedForUpdate.find(c => c.country === country)[type]) - 0.10).toFixed(2)
            this.limiterMin(country, type)
        }
    },
    computed: {
        relations() {
            return this.$page.props.resource.data.relations
        }
    }
}
</script>

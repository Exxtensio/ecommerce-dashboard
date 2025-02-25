<template>
    <div :class="{'required': isRequired}" class="readonly">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div class="grid gap-4 grid-cols-2 border theme-brand-border-color rounded-md p-4">
            <div v-for="(item, index) in field.resolvedForUpdate" class="relative border theme-brand-border-color rounded-md p-4">
                <div class="head-label absolute right-[16px] top-[12px]">
                    <span>#{{ index + 1 }}</span>
                </div>
                <label class="head-label flex gap-3 items-center justify-start mb-4">
                    <img class="w-5" v-if="countrySVG(item.country)" :src="countrySVG(item.country)" :alt="item.country"/>
                    <span>{{ countryName(item.country) }}</span>
                </label>
                <div class="space-y-3">
                    <div class="text-field detail-component xs">
                        <fwb-input
                            :model-value="item.name"
                            label="Product Name"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="item.quantity"
                            label="Quantity"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="item.item_price"
                            label="Price in the cart"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="item.price"
                            label="Price"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="item.stock"
                            label="In Stock"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="remain(item)"
                            label="Remain in Stock"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="field.resolvedForUpdate.length" class="text-sm text-left max-w-[300px] mr-auto mt-[5px]">
            <div class="py-2">{{ $t('Total Items:') }} {{ field.resolvedForUpdate.length }}</div>
            <div class="py-2 border-t theme-brand-border-color">{{ $t('Total Quantity:') }} {{ totalQuantity }}</div>
            <div class="py-2 border-t theme-brand-border-color">{{ $t('Total Price:') }} {{ totalSum }}</div>
        </div>
    </div>
</template>

<script>
import Base from '@/mixins/Base.js'

export default {
    mixins: [Base],
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    created() {
        this.field.resolvedForUpdate = this.field.resolvedForUpdate.map(item => ({
            ...item,
            price: parseFloat(item.price).toFixed(2),
            stock: parseFloat(item.stock).toFixed(2)
        }))
    },
    methods: {
        remain(item) {
            return parseFloat(item.stock - item.quantity).toFixed(2)
        },
        country(code) {
            return this.relations.hasOwnProperty('countries') ? this.relations.countries.find(c => c.code === code) : null
        },
        countryName(code) {
            if (!this.country(code)) return code
            return this.country(code).name
        },
        countrySVG(code) {
            return this.country(code) ? this.country(code).flag : null
        }
    },
    computed: {
        relations() {
            return this.$page.props.resource.data.relations
        },
        totalSum() {
            return this.field.resolvedForUpdate.reduce((sum, item) => {
                return sum + parseFloat(item.price) * parseFloat(item.quantity);
            }, 0)
        },
        totalQuantity() {
            return this.field.resolvedForUpdate.reduce((sum, item) => {
                return sum + parseFloat(item.quantity);
            }, 0)
        }
    }
}
</script>

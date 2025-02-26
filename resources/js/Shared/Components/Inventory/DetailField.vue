<template>
    <div :class="{'required': isRequired}" class="readonly">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div class="grid gap-4 grid-cols-2 border theme-brand-border-color rounded-md p-4">
            <div v-for="inventory in field.resolvedForUpdate" class="border theme-brand-border-color rounded-md p-4">
                <label class="head-label flex gap-3 items-center justify-start mb-4">
                    <img class="w-5" v-if="countrySVG(inventory.country)" :src="countrySVG(inventory.country)" :alt="inventory.country"/>
                    <span>{{ countryName(inventory.country) }}</span>
                </label>
                <div class="space-y-3">
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="inventory.price"
                            :label="$t('Price')"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                    <div class="number-field detail-component xs">
                        <fwb-input
                            :model-value="inventory.stock"
                            :label="$t('Stock')"
                            size="sm"
                            class="w-full input"
                            :disabled="true"
                        />
                    </div>
                </div>
            </div>
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
        }
    }
}
</script>

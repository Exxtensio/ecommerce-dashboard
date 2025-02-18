<template>
    <div class="readonly" :class="{'required': isRequired}">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div class="space-y-4 p-4 border theme-brand-border-color rounded-md">
            <div v-for="(items, key) in field.resolvedForDisplay">
                <div class="mb-2 head-label">
                    <span>{{ key }}</span>
                </div>
                <fwb-table :class="[`${field.component}-table`]">
                    <fwb-table-head>
                        <fwb-table-head-cell>{{ field.keyLabel }}</fwb-table-head-cell>
                        <fwb-table-head-cell>{{ field.valueLabel }}</fwb-table-head-cell>
                    </fwb-table-head>
                    <fwb-table-body>
                        <fwb-table-row v-if="show(key, items)" v-for="(item, key) in items">
                            <fwb-table-cell>
                                <div class="text-left">{{ key }}</div>
                            </fwb-table-cell>
                            <fwb-table-cell>
                                <div class="text-left">{{ item }}</div>
                            </fwb-table-cell>
                        </fwb-table-row>
                        <fwb-table-row v-else>
                            <fwb-table-cell>
                                <div class="text-left">
                                    There is no data
                                </div>
                            </fwb-table-cell>
                        </fwb-table-row>
                    </fwb-table-body>
                </fwb-table>
            </div>
        </div>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    methods: {
        show(k, v) {
            return k === 'old' && v.length || k === 'attributes' && v
        },
    }
}
</script>

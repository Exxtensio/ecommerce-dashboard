<template>
    <div :class="{'required': isRequired}" class="readonly">
        <label class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <fwb-table>
            <fwb-table-head>
                <fwb-table-head-cell>{{ field.keyLabel }}</fwb-table-head-cell>
                <fwb-table-head-cell>{{ field.valueLabel }}</fwb-table-head-cell>
            </fwb-table-head>
            <fwb-table-body>
                <fwb-table-row
                    v-if="field.resolvedForDisplay && Object.keys(field.resolvedForDisplay).length"
                    v-for="(value, key) in field.resolvedForDisplay"
                >
                    <fwb-table-cell>
                        <div class="text-left">{{ key }}</div>
                    </fwb-table-cell>
                    <fwb-table-cell>
                        <div class="text-left">{{ value }}</div>
                    </fwb-table-cell>
                </fwb-table-row>
                <fwb-table-row v-else>
                    <fwb-table-cell></fwb-table-cell>
                    <fwb-table-cell></fwb-table-cell>
                </fwb-table-row>
            </fwb-table-body>
        </fwb-table>
        <exx-help-text :text="field.helpText" type="text"/>
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
    }
}
</script>

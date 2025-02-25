<template>
    <div :class="{'required': isRequired, 'error': localErrorMessage}">
        <label
            class="block mb-2 text-sm font-medium"
            :class="{
                'text-gray-900 dark:text-white': !localErrorMessage,
                'text-red-700 dark:text-red-500': localErrorMessage
            }"
        >{{ field.name }}</label>
        <div class="space-y-4 p-4 border theme-brand-border-color rounded-md">
            <fwb-table class="detail-table">
                <fwb-table-head>
                    <fwb-table-head-cell>{{ field.keyLabel }}</fwb-table-head-cell>
                    <fwb-table-head-cell>{{ field.valueLabel }}</fwb-table-head-cell>
                </fwb-table-head>
                <fwb-table-body>
                    <fwb-table-row v-for="(item, index) in field.resolvedForUpdate">
                        <fwb-table-cell>
                            <div class="text-left">
                                <input
                                    class="bg-transparent outline-none"
                                    :value="item.key"
                                    @input="update('key', index, $event.target.value)"
                                />
                            </div>
                        </fwb-table-cell>
                        <fwb-table-cell>
                            <div class="text-left flex justify-between">
                                <input
                                    class="bg-transparent outline-none"
                                    :value="item.value"
                                    @input="update('value', index, $event.target.value)"
                                />
                                <button v-if="field.canDeleteRow" @click="removeRow(item.key)">
                                    <!--ToDo replace Heroicon-->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                         class="size-5 text-blue-600 dark:text-gray-400 hover:text-gray-400 dark:hover:text-white">
                                        <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </fwb-table-cell>
                    </fwb-table-row>
                </fwb-table-body>
            </fwb-table>
            <fwb-button v-if="field.canAddRow" @click="addRow" size="sm" class="w-full">{{ field.actionText }}</fwb-button>
        </div>

        <exx-help-text v-if="!localErrorMessage" :text="field.helpText" type="text"/>
        <exx-help-validation :text="localErrorMessage" type="text"/>
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
        },
        errors: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            localErrorMessage: null,
        };
    },
    methods: {
        removeRow(key) {
            this.field.resolvedForUpdate = this.field.resolvedForUpdate.filter(item => item.key !== key)
        },
        addRow() {
            this.field.resolvedForUpdate.push({key: '', value: ''})
        },
        update(type, index, value) {
            this.field.resolvedForUpdate[index][type] = value
        }
    },
    watch: {
        errors: {
            deep: true,
            immediate: true,
            handler(newErrors) {
                this.localErrorMessage = newErrors && newErrors.hasOwnProperty(this.field.attribute)
                    ? newErrors[this.field.attribute][0]
                    : null
            },
        },
    }
}
</script>

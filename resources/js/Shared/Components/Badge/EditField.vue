<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly, 'error': localErrorMessage}">
        <fwb-select
            v-model="field.resolvedForUpdate"
            :label="field.name"
            :options="field.options"
            size="sm"
            class="w-full"
            :placeholder="field.placeholder"
            :disabled="field.readonly"
            :validation-status="localErrorMessage ? 'error' : null"
        >
            <template v-if="!localErrorMessage && field.helpText" #helper>
                <exx-help-text :text="field.helpText"/>
            </template>
            <template v-if="localErrorMessage" #validationMessage>
                <exx-help-validation :text="localErrorMessage"/>
            </template>
        </fwb-select>
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
    },
}
</script>

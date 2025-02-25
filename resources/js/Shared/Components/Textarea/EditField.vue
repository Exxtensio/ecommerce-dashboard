<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly, 'error': localErrorMessage}">
        <fwb-textarea
            v-model="field.resolvedForUpdate"
            :label="field.name"
            :rows="field.rows"
            :placeholder="field.placeholder"
            size="sm"
            :disabled="field.readonly"
        />
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
            localErrorMessage: null
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
    }
}
</script>

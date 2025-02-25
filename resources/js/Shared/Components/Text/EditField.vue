<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly, 'error': localErrorMessage}">
        <fwb-input
            v-model="field.resolvedForUpdate"
            :label="field.name"
            :placeholder="field.placeholder"
            size="sm"
            :disabled="field.readonly"
            :validation-status="localErrorMessage ? 'error' : null"
        >
            <template v-if="!localErrorMessage && field.helpText" #helper>
                <exx-help-text :text="field.helpText"/>
            </template>
            <template v-if="localErrorMessage" #validationMessage>
                <exx-help-validation :text="localErrorMessage"/>
            </template>
        </fwb-input>
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
        field: {
            deep: true,
            immediate: true,
            handler(field) {
                if (this.pageType === 'create') {
                    let slugField = this.$_.first(this.$resourceStore.columns.filter(c => c.from === field.attribute))
                    if(slugField) this.emitter.emit('slugify', field.resolvedForUpdate)
                }
            },
        },
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

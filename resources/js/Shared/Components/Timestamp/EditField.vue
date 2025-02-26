IndexField.vue
<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly}">
        <fwb-input
            v-model="field.resolvedForUpdate"
            :label="field.name"
            :placeholder="field.placeholder"
            size="sm"
            :disabled="field.readonly"
        >
            <template v-if="field.helpText" #helper>
                <exx-help-text :text="field.helpText"/>
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
            default: {}
        }
    }
}
</script>

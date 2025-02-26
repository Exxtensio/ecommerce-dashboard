<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly}">
        <fwb-checkbox
            v-model="field.resolvedForUpdate"
            :label="field.name"
        />
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
        },
        errors: {
            type: Object,
            default: {}
        }
    }
}
</script>

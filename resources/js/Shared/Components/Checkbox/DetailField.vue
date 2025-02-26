<template>
    <div class="flex items-end readonly" :class="{'required': isRequired}">
        <fwb-checkbox
            :model-value="field.resolvedForDisplay"
            :label="field.name"
            :disabled="true"
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
        }
    }
}
</script>

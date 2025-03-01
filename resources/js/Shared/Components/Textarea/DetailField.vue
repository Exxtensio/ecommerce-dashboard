<template>
    <div class="readonly" :class="{'required': isRequired}">
        <fwb-textarea
            :model-value="field.resolvedForUpdate"
            :label="field.name"
            :rows="field.rows"
            placeholder=""
            size="sm"
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

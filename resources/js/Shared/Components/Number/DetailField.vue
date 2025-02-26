<template>
    <div class="readonly" :class="{'required': isRequired}">
        <fwb-input
            :model-value="field.resolvedForDisplay"
            :label="field.name"
            :placeholder="field.placeholder"
            size="sm"
            class="w-full"
            :disabled="true"
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
        }
    }
}
</script>

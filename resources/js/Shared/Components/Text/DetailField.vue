<template>
    <div class="flex items-end readonly" :class="{'required': isRequired}">
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
        <exx-copy-button :field="field" class="p-[10px]"/>
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

<template>
    <div :class="{'required': isRequired}" class="readonly">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <fwb-img
            v-if="field.resolvedForDisplay"
            img-class="rounded-md border theme-brand-border-color"
            :src="field.resolvedForDisplay"
        />
        <p v-else class="text-sm py-2">{{ $t('There is no image') }}</p>
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

<template>
    <div class="readonly" :class="{'required': isRequired}">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div v-if="field.resolvedForDisplay.length" class="grid gap-2 grid-cols-3">
            <div v-for="(file, index) in field.resolvedForDisplay">
                <fwb-img
                    img-class="rounded-md border theme-brand-border-color h-[150px] w-full object-contain"
                    :src="file.src"
                />
            </div>
        </div>
        <p v-else class="text-sm py-2">There are no images</p>
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
    },
    created() {
        if (!this.field.resolvedForDisplay) this.field.resolvedForDisplay = []
    }
}
</script>

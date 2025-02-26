<template>
    <th scope="row" class="font-medium text-gray-900 whitespace-nowrap dark:text-white">
        <div class="flex items-center space-x-2">
            <exx-copy-button :field="field"/>
            <a v-if="can(field.resolvedForDisplay, 'canPreview') && !isDeleted" class="btn-link" :href="showLink">
                {{ field.resolvedForDisplay }}
            </a>
            <div v-else>{{ field.resolvedForDisplay }}</div>
        </div>
    </th>
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
        isDeleted: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        showLink() {
            return `${this.prefix}/${this.field.resolvedForDisplay}`
        }
    }
}
</script>

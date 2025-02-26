<template>
    <td>
        <div>
            <a v-if="field.relation && field.relation.canPreview && exists" class="btn-link" :href="showLink">
                {{ title }}
            </a>
            <div v-else-if="field.relation && title">{{ title }}</div>
        </div>
    </td>
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
    computed: {
        showLink() {
            return this.getAdminUrl(`${this.field.relation.prefix}/${this.field.value.id}`)
        },
        exists() {
            return (this.field.value && this.field.value.hasOwnProperty('deleted_at') && !this.field.value.deleted_at) || this.field.value
        },
        title() {
            return this.field.value?.[this.field.relation.title]
        }
    }
}
</script>

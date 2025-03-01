<template>
    <td>
        <div>
            <a v-if="field.relation && field.relation.canPreview && exists" class="btn-link" :href="showLink">
                {{ title }}
            </a>
            <div v-else-if="field.relation">{{ title }}</div>
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
            if (this.field.value && this.field.value.hasOwnProperty(this.field.relation.title))
                return `${this.field.relation.singularLabel}: ${this.field.value[this.field.relation.title]}`
            else if (this.field.morphValue && this.field.morphValue.hasOwnProperty('id') && this.field.morphValue.id)
                return `${this.field.relation.singularLabel}: ${this.field.morphValue.id}`
            return '';
        }
    }
}
</script>

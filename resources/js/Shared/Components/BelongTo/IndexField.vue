<template>
    <td>
        <div>
            <a v-if="field.relation && field.relation.canPreview" class="btn-link" :href="showLink">
                {{ selected }}
            </a>
            <div v-else>{{ selected }}</div>
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
    created() {
        if (!this.field.options.length && this.relations.hasOwnProperty(this.field.relation.prefix))
            this.field.options = this.relations[this.field.relation.prefix]
    },
    computed: {
        relations() {
            return this.$page.props.resource.data.relations
        },
        selected() {
            const result = this.field.options.filter(o => o.value === this.field.value)
            if (result.length)
                return this.field.options.filter(o => o.value === this.field.value)[0].name
            return ''
        },
        showLink() {
            return this.getAdminUrl(`${this.field.relation.prefix}/${this.field.value}`)
        },
    }
}
</script>

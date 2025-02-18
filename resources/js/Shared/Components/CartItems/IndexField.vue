<template>
    <td>
        <div v-if="length > 0" class="btn-link">
            {{ length }} items
        </div>
    </td>
</template>

<script>

export default {
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        length() {
            return this.field.resolvedForDisplay ? this.field.resolvedForDisplay.length : 0
        },
        relations() {
            return this.$page.props.resource.data.relations
        }
    }
}
</script>

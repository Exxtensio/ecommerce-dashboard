<template>
    <td>
        <div v-if="event === 'updated'" class="btn-link">
            {{ $t('{count} changes', {count: changesLength}) }}
        </div>
    </td>
</template>

<script>

export default {
    props: {
        field: {
            type: Object,
            required: true
        },
        event: {
            type: String,
            default: null
        }
    },
    computed: {
        changesLength() {
            return this.field.resolvedForDisplay.hasOwnProperty('attributes')
                ? Object.keys(this.field.resolvedForDisplay.attributes).length
                : 0
        }
    }
}
</script>

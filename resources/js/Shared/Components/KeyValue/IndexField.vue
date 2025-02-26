<template>
    <td>
        <div class="btn-link">
            {{ $t('{count} items', {count: length}) }}
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
            return this.field.resolvedForDisplay ? Object.keys(this.field.resolvedForDisplay).length : 0
        }
    }
}
</script>

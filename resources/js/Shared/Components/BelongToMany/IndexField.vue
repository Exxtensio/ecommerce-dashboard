<template>
    <td>
        <div class="btn-link">
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
        }
    }
}
</script>

<template>
    <td :class="{'truncate': field.truncated}">
        <div class="flex items-center space-x-2">
            <exx-copy-button :field="field"/>
            <div>{{ field.resolvedForDisplay }}</div>
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
    }
}
</script>

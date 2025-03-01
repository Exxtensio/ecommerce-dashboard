<template>
    <td>
        <div>{{ field.resolvedForDisplay }}</div>
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

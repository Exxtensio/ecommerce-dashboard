<template>
    <td>
        <div>
            <star-rating
                v-model:rating="field.resolvedForDisplay"
                :show-rating="false"
                read-only
                star-size="14"
                active-color="#eab308"
                inactive-color="transparent"
            />
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

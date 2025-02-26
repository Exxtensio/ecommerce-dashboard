<template>
    <span v-if="text && type === 'template'">{{ text }}</span>
    <p v-else-if="text && type === 'text'" class="text-red-600 dark:text-red-500 mt-1 text-xs">{{ text }}</p>
</template>

<script>
export default {
    props: {
        text: {
            type: String,
            default: null,
        },
        type: {
            type: String,
            default: 'template'
        }
    }
}
</script>

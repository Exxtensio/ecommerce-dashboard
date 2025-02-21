<template>
    <span v-if="text && type === 'template'">{{ text }}</span>
    <p v-else-if="text && type === 'text'" class="dark:text-gray-400 text-gray-700 mt-1 text-xs">{{ text }}</p>
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

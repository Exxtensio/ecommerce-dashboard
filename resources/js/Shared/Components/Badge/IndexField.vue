<template>
    <td>
        <div class="flex">
            <span
                class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                :class="badgeClasses[!field.badgeType ? 'default' : field.badgeType]"
            >
                {{ field.selected }}
            </span>
        </div>
    </td>
</template>

<script>

export default {
    data() {
        return {
            badgeClasses: {
                default: ['bg-gray-400/10', 'text-gray-400', 'ring-gray-400/10'],
                yellow: ['bg-yellow-400/30', 'text-yellow-700', 'ring-yellow-400/30', 'dark:bg-yellow-400/10', 'dark:text-yellow-500', 'dark:ring-yellow-400/10'],
                green: ['bg-green-500/20', 'text-green-600', 'ring-green-500/20', 'dark:bg-green-500/10', 'dark:text-green-400', 'dark:ring-green-500/20'],
                red: ['bg-red-400/20', 'text-red-600', 'ring-red-400/20', 'dark:bg-red-400/10', 'dark:text-red-400', 'dark:ring-red-400/10'],
            }
        }
    },
    props: {
        field: {
            type: Object,
            required: true
        }
    }
}
</script>

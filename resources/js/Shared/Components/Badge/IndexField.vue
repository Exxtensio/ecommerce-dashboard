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
                yellow: ['bg-yellow-400/10', 'text-yellow-500', 'ring-yellow-400/10'],
                green: ['bg-green-500/10', 'text-green-400', 'ring-green-500/20'],
                red: ['bg-red-400/10', 'text-red-400', 'ring-red-400/10'],
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

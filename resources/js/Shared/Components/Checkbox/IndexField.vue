<template>
    <td>
        <CheckCircleIcon v-if="field.resolvedForDisplay" class="text-green-700 dark:text-green-500 size-6"/>
        <XCircleIcon v-else class="size-6 text-red-600 dark:text-red-400 opacity-40"/>
    </td>
</template>

<script>

import {
    CheckCircleIcon,
    XCircleIcon
} from '@heroicons/vue/24/solid'

export default {
    components: {
        CheckCircleIcon,
        XCircleIcon
    },
    props: {
        field: {
            type: Object,
            required: true
        }
    }
}
</script>

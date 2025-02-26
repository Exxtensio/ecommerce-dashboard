<template>
    <td class="min-w-[90px]">
        <fwb-avatar-stack v-if="length">
            <fwb-avatar
                v-for="img in field.resolvedForDisplay.slice(0, 4)"
                :img="img.src"
                class="div-object-contain"
                stacked
            />
            <fwb-avatar-stack-counter v-if="countLength" :total="countLength" @click.prevent />
        </fwb-avatar-stack>
        <PhotoIcon v-else class="bg-[#eaeaea] dark:bg-gray-700 rounded-md p-1 h-[30px] opacity-30"/>
    </td>
</template>

<script>
import {
    PhotoIcon
} from '@heroicons/vue/24/solid'

export default {
    components: {
        PhotoIcon
    },
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    created() {
        if (!this.field.resolvedForDisplay) this.field.resolvedForDisplay = []
    },
    computed: {
        length() {
            return this.field.resolvedForDisplay.length
        },
        countLength() {
            const count = this.field.resolvedForDisplay.slice(0, 4).length
            return this.length - count
        }
    }
}
</script>

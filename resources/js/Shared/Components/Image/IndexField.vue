<template>
    <td class="min-w-[130px]">
        <div class="flex justify-center">
            <fwb-img
                v-if="field.resolvedForDisplay"
                img-class="h-[35px] object-cover"
                :src="field.resolvedForDisplay"
            />
            <PhotoIcon v-else class="bg-[#eaeaea] dark:bg-gray-700 rounded-md p-1 h-[30px] opacity-30"/>
        </div>
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
    }
}
</script>

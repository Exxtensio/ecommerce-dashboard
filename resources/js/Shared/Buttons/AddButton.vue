<template>
    <fwb-button v-if="canCreate" color="default" class="btn-primary" :href="createLink">
        <template #prefix>
            <PlusIcon class="size-5"/>
        </template>
        {{ name }}
    </fwb-button>
</template>

<script>
import Base from "@/mixins/Base.js";
import {
    PlusIcon
} from '@heroicons/vue/24/solid';

export default {
    mixins: [Base],
    components: {
        PlusIcon
    },
    computed: {
        name() {
            return `Add ${this.singularLabel}`
        },
        createLink() {
            return this.getResourceUrl('create')
        }
    }
}
</script>

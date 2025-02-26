<template>
    <fwb-button v-if="canCreate" color="default" class="btn-primary add-button" :href="createLink">
        <template #prefix>
            <PlusIcon class="size-5"/>
        </template>
        <span class="name">{{ name }}</span>
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
            return this.$t('Add {singularLabel}', {singularLabel: this.getCorrectInterpolation(this.singularLabel.toLowerCase())})
        },
        createLink() {
            return this.getResourceUrl('create')
        }
    }
}
</script>

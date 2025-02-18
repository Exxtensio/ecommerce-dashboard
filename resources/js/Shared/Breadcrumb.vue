<template>
    <div class="px-6 pt-6 pb-4 theme-brand-border-color border-b mb-6">
        <fwb-breadcrumb size="lg">
            <fwb-breadcrumb-item home :href="getAdminUrl('')">
                Home
            </fwb-breadcrumb-item>
            <fwb-breadcrumb-item :href="getResourceUrl('')">
                <template #arrow-icon>
                    <ChevronRightIcon class="size-4 mr-2"/>
                </template>
                {{ label }}
            </fwb-breadcrumb-item>
            <fwb-breadcrumb-item v-if="type === 'create'">
                <template #arrow-icon>
                    <ChevronRightIcon class="size-4 mr-2"/>
                </template>
                <span class="dark:text-gray-400 text-gray-700">New {{ singularLabel }}</span>
            </fwb-breadcrumb-item>
            <fwb-breadcrumb-item v-else>
                <template #arrow-icon>
                    <ChevronRightIcon class="size-4 mr-2"/>
                </template>
                <span class="dark:text-gray-400 text-gray-700">{{ type === 'show' ? 'Preview:' : 'Edit:' }}</span>
                <div class="ml-2 dark:text-gray-400 text-gray-700">{{ title }}</div>
            </fwb-breadcrumb-item>
        </fwb-breadcrumb>
    </div>
</template>

<script>
import {
    ChevronRightIcon
} from '@heroicons/vue/24/solid'
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    components: {
        ChevronRightIcon
    },
    props: {
        type: {
            type: String,
            required: true
        }
    },
    computed: {
        title() {
            const firstMatch = this.$_.first(this.$_.where(this.$page.props.resource.data.fields, {attribute: this.$page.props.resource.data.title}));
            if(this.type === 'edit') {
                return firstMatch.resolvedForDisplay ?? firstMatch.value
            } else if (this.type === 'show') {
                return firstMatch.resolvedValue ?? firstMatch.value
            }
        },
        label() {
            return this.$page.props.resource.data.label
        }
    }
}
</script>

<template>
    <div class="px-6 pt-6 pb-4 theme-brand-border-color border-b mb-6">
        <nav class="" aria-label="Breadcrumb" size="lg">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <button class="ml-1 inline-flex items-center text-sm font-medium dark:text-gray-400 text-gray-700 hover:text-gray-900 dark:hover:text-white"
                       @click="$router.visit(getAdminUrl(''))">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Home
                    </button>
                </li>
                <li class="inline-flex items-center">
                    <ChevronRightIcon class="size-4 mr-2"/>
                    <button class="ml-1 inline-flex items-center text-sm font-medium dark:text-gray-400 text-gray-700 hover:text-gray-900 dark:hover:text-white"
                       @click="$router.visit(getResourceUrl(''))">
                        {{ label }}
                    </button>
                </li>
                <li class="inline-flex items-center" v-if="type === 'create'">
                    <ChevronRightIcon class="size-4 mr-2"/>
                    <span class="ml-1 inline-flex items-center text-sm font-medium dark:text-gray-400 text-gray-700">
                        <span class="dark:text-gray-400 text-gray-700">
                            {{ $t('New {singularLabel}', {singularLabel: singularLabel}) }}
                        </span>
                    </span>
                </li>
                <li class="inline-flex items-center" v-else>
                    <ChevronRightIcon class="size-4 mr-2"/>
                    <span class="ml-1 inline-flex items-center text-sm font-medium dark:text-gray-400 text-gray-500">
                        <span class="dark:text-gray-400 text-gray-700">
                            {{ type === 'show' ? $t('Preview:') : $t('Edit:') }}
                        </span>
                        <span class="ml-2 dark:text-gray-400 text-gray-700">Mraz, Corkery and Swift</span>
                    </span>
                </li>
            </ol>
        </nav>
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
            if (this.type === 'edit') {
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

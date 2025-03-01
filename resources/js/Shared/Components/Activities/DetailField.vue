<template>
    <div class="readonly">
        <label class="block mb-4 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div v-if="list.length" class="px-2">
            <fwb-timeline>
                <fwb-timeline-item
                    v-for="item in list"
                    class="timeline"
                    :class="{'closed': item.hidden, 'opened': !item.hidden}"
                >
                    <fwb-timeline-point/>
                    <fwb-timeline-content>
                        <fwb-timeline-time>
                            <div class="flex space-x-1 relative top-[4px] justify-between">
                                <div class="flex text-xs space-x-1 truncate mr-2">
                                    <button class="btn-link" @click="item.hidden = !item.hidden">
                                        <ArrowsUpDownIcon class="size-4"/>
                                    </button>
                                    <div class="text-gray-900 dark:text-white">{{ causer(item) }}</div>
                                    <div class="text-green-700 dark:text-green-500">{{ item.event }}</div>
                                    <div>{{ subjectType(item) }}</div>
                                    <div v-if="subjectTitle(item)">{{ subjectTitle(item) }}</div>
                                </div>
                                <div class="text-xs truncate">{{ item.diffHumans }}</div>
                            </div>
                        </fwb-timeline-time>
                        <fwb-timeline-body v-if="!item.hidden && item.properties">
                            <div class="border theme-brand-border-color p-2 rounded-md mt-5">
                                <vue-json-pretty
                                    :data="properties(item)"
                                    :showLine="false"
                                    :showIcon="true"
                                    :showLength="true"
                                    :itemHeight="18"
                                    :deep="0"
                                    :theme="theme"
                                />
                            </div>
                        </fwb-timeline-body>
                    </fwb-timeline-content>
                </fwb-timeline-item>
            </fwb-timeline>
        </div>
        <p v-else class="text-sm py-2">{{ $t('There is no activity') }}</p>
    </div>
</template>

<script>

import {ArrowsUpDownIcon} from '@heroicons/vue/24/solid'
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    components: {
        ArrowsUpDownIcon
    },
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            list: []
        }
    },
    created() {
        if (this.field.resolvedForDisplay) {
            this.list = this.field.resolvedForDisplay
            this.list.forEach(i => i.hidden = false)
        }
    },
    methods: {
        causer(item) {
            return item.causer ? item.causer.name : this.$t('Anonymous')
        },
        subjectType(item) {
            return item.subject_type.split("\\").pop()
        },
        properties(item) {
            return item.properties
        },
        subjectTitle(item) {
            return item.subject ? item.subject[this.field.relation.title] : null
        },
        description(item) {
            return item.description.split("\\").pop()
        }
    }
}
</script>

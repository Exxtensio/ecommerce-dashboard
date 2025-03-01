<template>
    <div class="relative">
        <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">{{ filter.name }}</label>
        <div class="space-y-1" v-for="count in ['1','2','3','4','5']">
            <div class="flex items-center">
                <fwb-checkbox
                    :modelValue="filter.value === count"
                    @update:modelValue="update($event,count)"
                />
                <star-rating
                    v-if="theme === 'dark'"
                    :rating="count"
                    :show-rating="false"
                    read-only
                    star-size="14"
                    class="ml-3 -mt-[6px]"
                    active-color="#eab308"
                    inactive-color="transparent"
                />
            </div>
        </div>
        <p v-if="filter.helpText" class="mt-1 text-чы text-gray-500 dark:text-gray-400">{{ filter.helpText }}</p>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    props: {
        filter: {
            type: Object,
            required: true
        }
    },
    created() {
        if (this.selected.find(item => item.key === this.filter.relation)) {
            this.filter.value = this.selected.find(item => item.key === this.filter.relation).value
        }
    },
    methods: {
        update(checked, value) {
            this.filter.value = checked ? value : null
        }
    },
    computed: {
        selected() {
            return this.$page.props.resource.data.options.filters
        }
    }
}
</script>

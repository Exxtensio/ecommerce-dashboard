<template>
    <div class="relative">
        <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">{{ filter.name }}</label>
        <fwb-checkbox
            :modelValue="filter.value === '1'"
            @update:modelValue="update($event,'1')"
            label="True"
        />
        <fwb-checkbox
            :modelValue="filter.value === '0'"
            @update:modelValue="update($event, '0')"
            class="mt-1"
            label="False"
        />
        <p v-if="filter.helpText" class="mt-1 text-чы text-gray-500 dark:text-gray-400">{{ filter.helpText }}</p>
    </div>
</template>

<script>
export default {
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

<template>
    <div class="relative">
        <label class="block mb-2 text-xs font-medium text-gray-900 dark:text-white">{{ filter.name }}</label>
        <multiselect
            :modelValue="modelValue"
            @update:modelValue="update"
            :options="filter.options"
            :searchable="filter.searchable"
            :close-on-select="true"
            :allow-empty="true"
            :show-labels="true"
            class="w-full"
            track-by="value"
            label="name"
            v-bind="{
                ...(filter.attribute === 'attributes' ? { customLabel: nameWithKey } : {})
            }"
            :placeholder="filter.placeholder"
        >
            <template #caret>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                     class="absolute right-0 top-0 p-[12px] size-[36px] dark:text-gray-400 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5"/>
                </svg>
            </template>
        </multiselect>
        <p v-if="filter.helpText" class="mt-1 text-чы text-gray-500 dark:text-gray-400">{{ filter.helpText }}</p>
        <button v-if="filter.value" @click="filter.value = null" class="block ml-auto mt-1 text-xs font-semibold btn-link hover:underline lowercase" data-button="clear">
            {{ $t('Clear') }}
        </button>
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
        if (!this.filter.separatable && this.relations.hasOwnProperty(this.filter.attribute))
            this.filter.options = this.relations[this.filter.attribute]

        if (this.selected.find(item => item.key === this.filter.relation)) {
            this.filter.value = this.selected.find(item => item.key === this.filter.relation).value
        }
    },
    methods: {
        nameWithKey({name, key}) {
            return `${key} - ${name}`
        },
        update(value) {
            this.filter.value = value ? value.value : null
        }
    },
    computed: {
        relations() {
            return this.$page.props.resource.data.relations
        },
        selected() {
            return this.$page.props.resource.data.options.filters
        },
        modelValue() {
            return this.filter.options.find(o => o.value === this.filter.value) || null
        }
    }
}
</script>

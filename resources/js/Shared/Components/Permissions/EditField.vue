<template>
    <div :class="{'required': isRequired}">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div v-if="Object.keys(list).length" class="grid grid-cols-4 gap-4 border theme-brand-border-color rounded-md p-4 max-[1380px]:grid-cols-3 max-[1180px]:grid-cols-2">
            <div
                v-for="(items, group) in list"
                class="border theme-brand-border-color rounded-md p-4"
            >
                <fwb-checkbox
                    :label="toTitleCase(group)"
                    :model-value="items.every(item => item.checked)"
                    @update:modelValue="(checked) => items.forEach(item => (item.checked = checked))"
                    class="mb-2 head-label"
                />
                <div class="flex flex-col items-baseline pl-[28px] space-y-1">
                    <div v-for="item in items">
                        <fwb-checkbox
                            v-model="item.checked"
                            class="child-label"
                            :label="group === 'global' ? item.name : item.name.replace(capitalize(group), '').trim()"
                        />
                    </div>
                </div>
            </div>
        </div>
        <p v-else class="text-sm py-2">{{ $t('There are no permissions') }}</p>
        <exx-help-text :text="field.helpText" type="text"/>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            list: {}
        }
    },
    created() {
        if (!this.field.resolvedForUpdate) this.field.resolvedForUpdate = []
        this.list = this.groupBy(this.relations[this.field.attribute] || [], this.field.resolvedForUpdate) || {}
    },
    watch: {
        list: {
            deep: true,
            handler(value) {
                this.field.resolvedForUpdate = Object.values(value)
                    .flat()
                    .filter(item => item.checked)
                    .map(item => item.value)
            },
        },
    },
    computed: {
        relations() {
            return this.$page.props.resource.data.relations
        }
    }
}
</script>

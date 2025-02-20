<template>
    <div class="readonly" :class="{'required': isRequired}">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <div
            v-if="length"
            class="grid border theme-brand-border-color rounded-md p-4"
            :class="{
                'gap-2 grid-cols-3 max-[1380px]:grid-cols-2 max-[1180px]:grid-cols-1': needGroupingByRelation && list.length,
                'gap-4 grid-cols-2 max-[1380px]:grid-cols-1': needGroupingByRelation && (groupedList.length || ungroupedList.length),
                'gap-4 grid-cols-3 max-[1380px]:grid-cols-2 max-[1180px]:grid-cols-1': needGroupingByKey
            }"
        >
            <template v-if="needGroupingByKey">
                <div v-for="group in list" class="border theme-brand-border-color rounded-md p-4">
                    <label class="head-label flex gap-3 items-center justify-start mb-2">
                        <span>{{ group.group }}</span>
                    </label>
                    <div class="flex flex-col items-baseline pl-[28px] space-y-1">
                        <div v-for="item in group.items" class="grid">
                            <fwb-checkbox
                                :model-value="item.checked"
                                :label="item.name"
                                :disabled="true"
                                class="truncate"
                            />
                        </div>
                    </div>
                </div>
            </template>

            <!--Categories-->
            <template v-if="needGroupingByRelation && list.length">
                <div v-for="item in list">
                    <fwb-checkbox
                        :model-value="item.checked"
                        :label="item.name"
                        :disabled="true"
                        class="truncate"
                    />
                </div>
            </template>
            <template v-else-if="needGroupingByRelation && (groupedList.length || ungroupedList.length)">
                <div v-for="group in groupedList" class="border theme-brand-border-color rounded-md p-4">
                    <!--Parent-->
                    <fwb-checkbox
                        :label="group.name"
                        :model-value="areAllChecked(group)"
                        :disabled="true"
                        class="mb-2 truncate head-label"
                    />
                    <!--Children-->
                    <div v-if="group.children.length" class="flex flex-col items-baseline pl-[28px] space-y-1">
                        <div v-for="child in group.children" class="grid">
                            <fwb-checkbox
                                v-if="!child.children.length"
                                :model-value="child.checked"
                                :disabled="true"
                                :label="child.name"
                                class="truncate"
                            />
                            <template v-else>
                                <fwb-checkbox
                                    :label="child.name"
                                    :model-value="areAllChecked(child)"
                                    :disabled="true"
                                    class="mb-2 truncate"
                                />
                                <div v-if="child.children.length" class="flex flex-col items-baseline pl-[28px] space-y-1 mb-2">
                                    <div v-for="_child in child.children" class="grid">
                                        <fwb-checkbox
                                            :model-value="_child.checked"
                                            :label="_child.name"
                                            :disabled="true"
                                            class="truncate"
                                        />
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                <div v-if="ungroupedList" class="border theme-brand-border-color rounded-md p-4">
                    <label class="head-label flex gap-3 items-center justify-start mb-2">
                        <span>Without Group</span>
                    </label>
                    <div class="flex flex-col items-baseline pl-[28px] space-y-1">
                        <div v-for="item in ungroupedList" class="grid">
                            <fwb-checkbox
                                :model-value="item.checked"
                                :label="item.name"
                                :disabled="true"
                                class="truncate"
                            />
                        </div>
                    </div>
                </div>
            </template>
        </div>
        <p v-else class="text-sm py-2">
            {{ $t('There is no :attribute', {attribute: field.attribute}) }}
        </p>
    </div>
</template>

<script>
import Base from '@/mixins/Base.js'

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
            list: [],
            groupedList: [],
            ungroupedList: []
        }
    },
    created() {
        if (!this.field.resolvedForUpdate) this.field.resolvedForUpdate = []

        if(this.relations.hasOwnProperty(this.field.attribute) && this.relations[this.field.attribute].length) {
            if(this.needGroupingByKey) {
                this.list = this.groupByKey()
            } else if (this.needGroupingByRelation) {
                if(this.relations[this.field.attribute].filter(r => r.parent_id).length) {
                    this.groupedList = this.hierarchy().filter(h => h.children.length)
                    this.ungroupedList = this.hierarchy().filter(h => !h.children.length)
                } else {
                    this.list = this.hierarchy()
                }
            }
        }
    },
    methods: {
        areAllChecked(category) {
            if (!category.children || category.children.length === 0)
                return category.checked
            return category.checked && category.children.every(this.areAllChecked);
        },
        groupByKey() {
            const grouped = {}
            this.relations[this.field.attribute].forEach(item => {
                const { key, value, name } = item
                if (!grouped[key]) {
                    grouped[key] = {
                        group: key,
                        items: []
                    }
                }
                grouped[key].items.push({
                    value, name,
                    checked: this.field.resolvedForUpdate.includes(item.hasOwnProperty('value') ? item.value : item.id)
                })
            })

            return Object.values(grouped)
        },
        hierarchy() {
            const map = new Map();
            const result = [];

            this.relations[this.field.attribute].forEach(category => {
                map.set(category.value, {
                    name: category.name,
                    value: category.value,
                    checked: this.field.resolvedForUpdate.includes(category.hasOwnProperty('value') ? category.value : category.id),
                    children: []
                })
            })

            this.relations[this.field.attribute].forEach(category => {
                if (category.parent_id === null) {
                    result.push(map.get(category.value))
                } else if (map.has(category.parent_id)) {
                    const parent = map.get(category.parent_id)
                    const child = map.get(category.value)
                    child.checked = this.field.resolvedForUpdate.includes(child.hasOwnProperty('value') ? child.value : child.id)
                    parent.children.push(child)
                }
            })

            return result
        },
    },
    computed: {
        needGroupingByKey() {
            return Boolean(!this.field.groupRelation && this.field.groupAttribute)
        },
        needGroupingByRelation() {
            return Boolean(this.field.groupRelation && this.field.groupAttribute)
        },
        length() {
            return !!((this.needGroupingByKey && this.list.length) ||
                (this.needGroupingByRelation && (this.groupedList.length || this.ungroupedList.length)) ||
                (this.needGroupingByRelation && this.list.length));
        },
        relations() {
            return this.$page.props.resource.data.relations
        }
    }
}
</script>

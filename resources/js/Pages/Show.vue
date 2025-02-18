<template>
    <div>
        <div class="w-full antialiased">
            <exx-breadcrumb type="show"/>

            <div class="mx-6 max-w-[1200px]">
                <div class="mb-6 mt-10 flex items-center justify-between">
                    <div class="text-white space-y-2">
                        <div class="flex items-end space-x-4">
                            <h1 class="text-2xl dark:text-white text-gray-700">{{ title }}</h1>
                            <div v-if="can(getId, 'canEdit')">
                                <fwb-button size="xs" color="light" class="btn-light" :href="editLink">Edit</fwb-button>
                            </div>
                        </div>
                        <p class="text-sm dark:text-gray-400 text-gray-700">id: <span class="text-gray-400 dark:text-gray-500">{{ getId }}</span></p>
                    </div>
                </div>

                <div class="mx-[7px] text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                    <ul class="flex flex-wrap -mb-[2px]">
                        <li v-for="(name, key) in uniquePanels" class="me-10">
                            <button
                                @click="currentPanel = key"
                                class="inline-block py-4 border-b-2 rounded-t-lg"
                                :class="{
                                    'text-[var(--exx-primary-color)] dark:text-[var(--exx-dark-primary-color)] border-[var(--exx-primary-color)] dark:border-[var(--exx-dark-primary-color)]': currentPanel === key,
                                    'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 border-transparent': currentPanel !== key
                                }"
                            >
                                {{ name }}
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="theme-brand-bg rounded-md p-9 mb-10">
                    <div v-for="(name, key) in uniquePanels" v-show="currentPanel === key" class="grid grid-cols-2 gap-6 items-baseline">
                        <template v-for="field in fields.filter(f => f.panel === key)">
                            <component
                                v-if="field.showOnDetail"
                                class="component detail-component"
                                :is="resolveComponent(field.component, 'detail')"
                                :class="[field.width, field.component]"
                                v-bind="{
                                    ...(field.dependOn ? { dependOnComponent: dependComponent(fields, field.dependOn) } : {})
                                }"
                                :field="field"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Base from '@/mixins/Base.js';
import Layout from '@/Shared/Layout.vue';
import Depend from "@/mixins/Depend.js";
import ExxBreadcrumb from "@/Shared/Breadcrumb.vue";

export default {
    components: {ExxBreadcrumb},
    mixins: [Base, Depend],
    layout: Layout,
    data() {
        return {
            currentPanel: 'overview',
            fields: JSON.parse(JSON.stringify(this.resource.data.fields.filter(i => i.attribute !== 'deleted_at'))),
        }
    },
    beforeMount() {
        this.$resourceStore.set(this.$page.props.resource)
    },
    props: {
        resource: {
            type: Array,
            required: true
        }
    },
    computed: {
        uniquePanels() {
            return Object.fromEntries(
                Object.entries(this.appPanels).filter(([key]) => this.$_.uniq(this.$_.pluck(this.resource.data.fields, 'panel')).includes(key))
            );
        },
        title() {
            const firstMatch = this.$_.first(this.$_.where(this.resource.data.fields, {attribute: this.resource.data.title}));
            return firstMatch.resolvedForDisplay ?? firstMatch.value
        },
        getId() {
            return this.fields.filter(i => i.component === 'id-field')[0].value
        },
        editLink() {
            return this.getResourceUrl(this.getId + '/edit')
        }
    }
}
</script>

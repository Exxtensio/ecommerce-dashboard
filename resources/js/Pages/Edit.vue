<template>
    <div>
        <div class="w-full antialiased min-w-[740px] overflow-x-auto" style="min-height: calc(100vh - 4rem);">
            <exx-breadcrumb type="edit"/>

            <div class="mx-6 max-w-[1200px]">
                <div class="mb-6 mt-10 flex items-center justify-between max-[1320px]:flex-col max-[1320px]:items-baseline max-[1320px]:space-y-4">
                    <div class="text-white space-y-2">
                        <div class="flex items-end space-x-4">
                            <h1 class="text-2xl dark:text-white text-gray-700">{{ title }}</h1>
                            <div v-if="can(getId, 'canPreview')">
                                <fwb-button size="xs" color="light" class="btn-light" :href="showLink">{{ $t('Preview') }}</fwb-button>
                            </div>
                        </div>
                        <p class="text-sm dark:text-gray-400 text-gray-700">id: <span class="text-gray-400 dark:text-gray-500">{{ getId }}</span></p>
                    </div>
                    <div>
                        <div class="flex items-center justify-end gap-x-2 max-[1000px]:flex-col max-[1000px]:gap-x-0 max-[1000px]:items-baseline max-[1000px]:gap-y-2">
                            <fwb-button color="light" class="btn-light" @click="revert">{{ $t('Revert changes') }}</fwb-button>
                            <fwb-button color="light" class="btn-light" @click="update(false)">{{ $t('Save & Continue Editing') }}</fwb-button>
                            <fwb-button color="default" class="btn-success" @click="update(true)">{{ $t('Save') }}</fwb-button>
                        </div>
                    </div>
                </div>

                <div class="mx-[7px] text-sm font-medium text-center text-gray-500 dark:text-gray-400">
                    <ul class="flex flex-wrap -mb-[2px]">
                        <li v-for="(name, key) in uniquePanels" class="me-10 max-[1140px]:me-6">
                            <button
                                @click="currentPanel = key"
                                class="inline-block py-4 border-b-2 rounded-t-lg"
                                :class="{
                                    'text-[var(--exx-primary-color)] dark:text-[var(--exx-dark-primary-color)] border-[var(--exx-primary-color)] dark:border-[var(--exx-dark-primary-color)]': currentPanel === key,
                                    '!text-[var(--exx-danger-color)] !border-[var(--exx-danger-color)]': errorPanels.includes(key),
                                    'hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300 border-transparent': currentPanel !== key
                                }"
                            >
                                {{ $t(name) }}
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="theme-brand-bg rounded-md p-9 mb-10">
                    <div v-for="(name, key) in uniquePanels" v-show="currentPanel === key" class="grid grid-cols-2 gap-6 items-baseline max-[1180px]:grid-cols-1">
                        <template v-for="field in fields.filter(f => f.panel === key)">
                            <component
                                v-if="field.showOnUpdate"
                                class="component edit-component max-[1180px]:col-auto"
                                :is="resolveComponent(field.component, 'edit')"
                                :class="[field.width, field.component]"
                                v-bind="{
                                    ...(field.dependOn ? { dependOnComponent: dependComponent(fields, field.dependOn) } : {})
                                }"
                                :field="field"
                                :errors="errors"
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
import Depend from "@/mixins/Depend.js";
import Layout from '@/Shared/Layout.vue';
import axios from "axios";
import {router} from "@inertiajs/vue3";

export default {
    mixins: [Base, Depend],
    layout: Layout,
    data() {
        return {
            currentPanel: 'overview',
            errorPanels: [],
            errors: {},
            fields: JSON.parse(JSON.stringify(this.resource.data.fields)),
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
    methods: {
        revert() {
            this.errors = {}
            this.errorPanels = []
            this.fields = JSON.parse(JSON.stringify(this.resource.data.fields))
        },
        async update(redirect) {
            this.errors = {}
            this.errorPanels = []

            await axios.post(this.getResourceUrl(this.getId), this.dataToUpdate)
                .then(response => {
                    this.emitter.emit('notify', {
                        type: 'success',
                        message: this.$t(':singularLabel updated successfully', {singularLabel: this.singularLabel})
                    })
                    if (redirect) router.visit(this.getResourceUrl(''))
                }).catch(errors => {
                    if (errors.response.status === 422) {
                        this.errors = errors.response.data.errors

                        this.errorPanels = this.$_.pluck(
                            this.resource.data.fields.filter(f => Object.keys(this.errors).includes(f.attribute)),
                            'panel'
                        )
                    } else {
                        this.emitter.emit('notify', {
                            type: 'danger',
                            message: errors.response.data.message
                        })
                    }
                })
        }
    },
    computed: {
        uniquePanels() {
            return Object.fromEntries(
                Object.entries(this.appPanels).filter(([key]) => this.$_.uniq(this.$_.pluck(this.resource.data.fields, 'panel')).includes(key) && key !== 'activities')
            );
        },
        title() {
            const firstMatch = this.$_.first(this.$_.where(this.resource.data.fields, {attribute: this.resource.data.title}));
            return firstMatch.resolvedForDisplay ?? firstMatch.value
        },
        dataToUpdate() {
            const formData = new FormData();
            this.$_.map(
                this.fields.filter(i => i.showOnUpdate),
                function (item) {
                    if (item.component === 'image-field' && item.file) {
                        formData.append(item.attribute, item.file)
                    } else if (item.component === 'gallery-field' && item.resolvedForDisplay.length) {
                        const resolved = JSON.parse(JSON.stringify(item.resolvedForDisplay));
                        resolved.forEach(i => {
                            delete i.file
                            const currentFile = item.files.find(f => f.id === i.id) || null
                            formData.append(`${item.attribute}[]`, JSON.stringify(i));
                            if(currentFile) formData.append(i.id, currentFile);
                        })
                    } else if (item.component === 'inventory-field') {
                        formData.append(item.attribute, JSON.stringify(item.resolvedForUpdate));
                    } else if (Array.isArray(item.resolvedForUpdate)) {
                        item.resolvedForUpdate.forEach(i => {
                            formData.append(`${item.attribute}[]`, i);
                        })
                    } else if (item.component !== 'image-field') {
                        let val = item.resolvedForUpdate
                        if (typeof val === 'boolean')
                            val = item.resolvedForUpdate ? '1' : '0'
                        formData.append(item.attribute, !val ? '' : val)
                    }
                }
            )
            return formData
        },
        getId() {
            return this.resource.data.fields.filter(i => i.component === 'id-field')[0].value
        },
        showLink() {
            return this.getResourceUrl(this.getId)
        }
    }
}
</script>

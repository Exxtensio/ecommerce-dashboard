<template>
    <div>
        <div class="w-full antialiased">
            <exx-breadcrumb type="create"/>

            <div class="mx-6 max-w-[1200px]">
                <div class="mb-6 mt-10 flex items-center justify-between">
                    <div class="text-white space-y-2">
                        <div class="flex items-end space-x-4">
                            <h1 class="text-2xl">New {{ singularLabel }}</h1>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-end gap-x-2">
                            <fwb-button color="light" class="btn-light" @click="create(false)">Create & Continue Creating</fwb-button>
                            <fwb-button color="default" class="btn-success" @click="create(true)">Create</fwb-button>
                        </div>
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
                                    '!text-[var(--exx-danger-color)] !border-[var(--exx-danger-color)]': errorPanels.includes(key),
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
                        <template v-for="field in columns.filter(f => f.panel === key)">
                            <component
                                v-if="field.showOnCreation"
                                class="component edit-component"
                                :is="resolveComponent(field.component, 'edit')"
                                :class="[field.width, field.component]"
                                :field="field"
                                v-bind="{
                                    ...(field.dependOn ? { dependOnComponent: dependComponent(columns, field.dependOn) } : {})
                                }"
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
            columns: JSON.parse(JSON.stringify(this.$resourceStore.columns)),
        }
    },
    beforeMount() {
        this.$resourceStore.set(this.resource)
    },
    props: {
        resource: {
            type: Array,
            required: true
        },
        extentOfLimitations: {
            type: Boolean,
            required: true,
            default: false,
        }
    },
    methods: {
        async create(redirect) {
            if(this.extentOfLimitations) {
                this.emitter.emit('notify', {
                    type: 'danger',
                    message: 'Your license type does not allow you to create more resources'
                })
            } else {
                this.errors = {}
                this.errorPanels = []

                await axios.post(this.getResourceUrl(''), this.dataToCreate)
                    .then(response => {
                        this.emitter.emit('notify', {
                            type: 'success',
                            message: `${this.singularLabel} created successfully`
                        })
                        if (redirect) router.visit(this.getResourceUrl(''))
                    }).catch(errors => {
                        if (errors.response.status === 422) {
                            this.errors = errors.response.data.errors

                            this.errorPanels = this.$_.pluck(
                                this.columns.filter(f => Object.keys(this.errors).includes(f.attribute)),
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
        }
    },
    computed: {
        uniquePanels() {
            return Object.fromEntries(
                Object.entries(this.appPanels).filter(([key]) => this.$_.uniq(this.$_.pluck(this.columns, 'panel')).includes(key) && key !== 'activities')
            );
        },
        dataToCreate() {
            const formData = new FormData();
            this.$_.map(
                this.columns.filter(i => i.showOnCreation),
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
        }
    }
}
</script>

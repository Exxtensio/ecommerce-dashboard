<template>
    <div>
        <div class="w-full antialiased min-w-[740px] overflow-x-auto" style="min-height: calc(100vh - 4rem);">
            <exx-breadcrumb type="create"/>

            <div class="mx-6 max-w-[1200px]">
                <div class="mb-6 mt-10 flex items-center justify-between max-[1320px]:flex-col max-[1320px]:items-baseline max-[1320px]:space-y-4">
                    <div class="text-white space-y-2">
                        <div class="flex items-end space-x-4">
                            <h1 class="text-2xl">{{ $t('New {singularLabel}', {singularLabel: singularLabel}) }}</h1>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-end gap-x-2 max-[1000px]:flex-col max-[1000px]:gap-x-0 max-[1000px]:items-baseline max-[1000px]:gap-y-2">
                            <fwb-button color="light" class="btn-light" @click="create(false)">{{ $t('Create & Continue Creating') }}</fwb-button>
                            <fwb-button color="default" class="btn-success" @click="create(true)">{{ $t('Create') }}</fwb-button>
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
                        <template v-for="field in columns.filter(f => f.panel === key)">
                            <component
                                v-if="field.showOnCreation"
                                class="component edit-component max-[1180px]:col-auto"
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
                    message: this.$t('Your license type does not allow you to create more resources')
                })
            } else {
                this.errors = {}
                this.errorPanels = []

                await this.$axios.post(this.getResourceUrl(''), this.dataToCreate)
                    .then(response => {
                        this.emitter.emit('notify', {
                            type: 'success',
                            message: this.$t('{singularLabel} created successfully', {singularLabel: this.singularLabel})
                        })
                        if (redirect) this.$router.visit(this.getResourceUrl(''))
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

<template>
    <div class="mx-6 mb-14 rounded-md theme-brand-bg overflow-hidden">
        <exx-pagination v-if="dataToShow.length" :meta="meta"/>
        <div class="overflow-x-auto">
            <table
                class="w-full text-sm text-left border-t theme-brand-border-color"
                :class="{'min-w-[1400px]': dataToShow.length}"
            >
                <thead class="text-xs uppercase whitespace-nowrap theme-brand-border-color border-b  bg-[#f8fafc] dark:bg-[#1c2e45]">
                    <tr v-if="!dataToShow.length" class="h-[48.5px]">
                        <th colspan="100%"></th>
                    </tr>
                    <tr v-else>
                        <th scope="col" class="ps-6 pe-6 py-4 w-20">
                            <div class="flex items-center">
                                <input
                                    id="checkbox-all"
                                    type="checkbox"
                                    @input="selectAll"
                                    :checked="isCheckedAll()"
                                >
                                <label for="checkbox-all" class="sr-only">checkbox</label>
                            </div>
                        </th>
                        <th
                            scope="col"
                            class="pe-4 py-4"
                            :class="{
                                'text-center': column.component === 'image-field'
                            }"
                            v-for="column in columns"
                        >{{ column.name }}</th>
                        <th scope="col" class="pe-6 py-4">
                            <span class="sr-only">{{ $t('Actions') }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody :class="{'whitespace-nowrap': dataToShow.length}">
                <tr v-if="!dataToShow.length">
                    <td colspan="100%" class="w-full px-4 py-4">
                        <div class="flex flex-col justify-center items-center text-center space-y-4">
                            <div class="flex flex-col justify-center items-center text-center space-y-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                <span class="text-lg">{{ $t('Data not found') }}</span>
                                <span class="max-w-[280px]">{{ $t('Your selection did not match any data') }}</span>
                            </div>
                            <exx-add-button/>
                        </div>
                    </td>
                </tr>
                <tr
                    class="hover:bg-[#f8fafc] dark:hover:bg-[#17283e]"
                    :class="{'border-b theme-brand-border-color': index !== dataToShow.length - 1}"
                    v-for="(item, index) in dataToShow"
                >
                    <td class="ps-6 pe-6 py-4 w-20">
                        <div class="flex items-center">
                            <input
                                :id="`checkbox-table-${getId(item)}`"
                                type="checkbox"
                                @input="select($event, getId(item))"
                                :checked="isChecked(getId(item))"
                            >
                            <label :for="`checkbox-table-${getId(item)}`" class="sr-only">checkbox</label>
                        </div>
                    </td>

                    <component
                        v-for="i in item"
                        class="component index-component pe-4"
                        :key="`${i.component}-${getId(item)}`"
                        :is="resolveComponent(i.component, 'index')"
                        :class="[{
                            'py-5': i.component !== 'image-field' && i.component !== 'gallery-field'
                        }, i.component]"
                        v-bind="{
                            ...(i.component === 'id-field' ? { isDeleted: isDeleted(item) } : {}),
                            ...(i.component === 'activity-field' ? { event: getEvent(item) } : {}),
                            ...(i.dependOn ? { dependOnComponent: dependComponent(item, i.dependOn) } : {})
                        }"
                        :field="i"
                    />

                    <td class="pe-6 py-4">
                        <div class="flex items-center justify-end space-x-2">
                            <fwb-button v-if="can(getId(item), 'canPreview') && !isDeleted(item)" size="xs" color="light" :href="showLink(item)" class="btn-light">
                                {{ $t('Preview') }}
                            </fwb-button>
                            <fwb-button v-if="can(getId(item), 'canEdit') && !isDeleted(item)" size="xs" class="btn-primary" color="default" :href="editLink(item)">
                                {{ $t('Edit') }}
                            </fwb-button>
                            <fwb-button v-if="can(getId(item), 'canDelete') && !isDeleted(item)" size="xs" class="btn-dander" color="red" @click="deleteResource(item)">
                                {{ $t('Delete') }}
                            </fwb-button>
                            <fwb-button v-if="isDeleted(item)" size="xs" class="btn-success-outline" color="green" @click="restoreResource(item)" outline>
                                {{ $t('Restore') }}
                            </fwb-button>
                            <fwb-button v-if="isDeleted(item)" size="xs" class="btn-danger-outline" color="red" @click="forceDeleteResource(item)" outline>
                                {{ $t('Force Delete') }}
                            </fwb-button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import Depend from "@/mixins/Depend.js";

export default {
    mixins: [Base, Depend],
    methods: {
        async deleteResource(item) {
            await axios.delete(this.getResourceUrl(this.getId(item)))
                .then(response => {
                    this.emitter.emit('notify', {
                        type: 'success',
                        message: this.$t(':singularLabel deleted successfully', {singularLabel: this.singularLabel})
                    })
                    router.visit(this.getResourceUrl(''))
                })
        },
        async forceDeleteResource(item) {
            await axios.delete(this.getResourceUrl(this.getId(item) + '/force'))
                .then(response => {
                    this.emitter.emit('notify', {
                        type: 'success',
                        message: this.$t(':singularLabel force deleted successfully', {singularLabel: this.singularLabel})
                    })
                    router.visit(this.getResourceUrl(''))
                })
        },
        async restoreResource(item) {
            await axios.put(this.getResourceUrl(this.getId(item) + '/restore'))
                .then(response => {
                    this.emitter.emit('notify', {
                        type: 'success',
                        message: this.$t(':singularLabel restored successfully', {singularLabel: this.singularLabel})
                    })
                    router.visit(this.getResourceUrl(''))
                })
        },
        showLink(item) {
            return this.getResourceUrl(this.getId(item))
        },
        editLink(item) {
            return this.getResourceUrl(this.getId(item) + '/edit')
        },
        isChecked(id) {
            return this[`$${this.prefix}Store`].selected.includes(id)
        },
        isCheckedAll() {
            const array = this.data.map(item => this.getId(item));
            const selected = this[`$${this.prefix}Store`].selected;
            return array.every(id => selected.includes(id));
        },
        selectAll(e) {
            const array = this.data.map(item => this.getId(item));
            if (e.target.checked) this[`$${this.prefix}Store`].setSelected(array);
            else if (!e.target.checked) this[`$${this.prefix}Store`].removeSelected(array);
        },
        select(e, id) {
            if (e.target.checked) this[`$${this.prefix}Store`].setSelected([id]);
            else if (!e.target.checked) this[`$${this.prefix}Store`].removeSelected([id]);
        },
        getId(fields) {
            return fields.filter(i => i.component === 'id-field')[0].value
        },
        getEvent(fields) {
            return fields.filter(i => i.attribute === 'event')[0].value || null
        },
        isDeleted(fields) {
            const array = fields.filter(i => i.attribute === 'deleted_at')
            return !!(array && array.length && array[0].value);
        }
    },
    computed: {
        columns() {
            return this.dataToShow[0]
        },
        dataToShow() {
            return this.$resourceStore.searchEnabled
                ? this.$resourceStore.searchData.map(item => {
                    return item.filter(i => i.showOnIndex)
                })
                : this.$resourceStore.data.map(item => {
                    return item.filter(i => i.showOnIndex)
                })
        },
        data() {
            return this.$resourceStore.searchEnabled
                ? this.$resourceStore.searchData
                : this.$resourceStore.data
        },
        meta() {
            return this.$resourceStore.searchEnabled
                ? this.$resourceStore.searchMeta
                : this.$resourceStore.meta
        }
    }
}
</script>

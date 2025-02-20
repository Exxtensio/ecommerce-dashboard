<template>
    <div v-if="count" class="flex items-center space-x-3 relative" v-click-away="onClickAway">
        <button
            @click="open = !open"
            class="w-full md:w-auto btn-default flex items-center justify-center py-2 px-4 text-sm focus:z-10 max-2xl:h-[38px]"
            type="button"
        >
            <BoltIcon class="size-5 -ml-1 mr-2 max-2xl:mr-1"/>
            <span class="mr-2 max-2xl:hidden">{{ $t('Actions') }}</span>{{ count }}
            <ChevronDownIcon class="-mr-1 ml-2 w-3.5 h-3.5 max-2xl:ml-1"/>
        </button>
        <div class="z-10 min-w-44 absolute right-0 top-[43px] theme-brand-dropdown !divide-y-0 max-lg:right-auto max-lg:left-0" :class="{'hidden': !open}">
            <h6 class="px-4 pt-3 font-medium text-xs btn-green-link select-none">{{ count }} {{ $t('item(s) selected') }}</h6>
            <ul class="py-1 text-sm">
                <li>
                    <button @click="callModal('delete')">{{ $t('Delete All') }}</button>
                </li>
                <li v-if="hasSoftDelete">
                    <button @click="callModal('force')">{{ $t('Force Delete All') }}</button>
                </li>
                <li v-if="hasSoftDelete">
                    <button @click="performAction('restore')">{{ $t('Restore All') }}</button>
                </li>
                <li v-if="count">
                    <button @click="unselect">{{ $t('Unselect All') }}</button>
                </li>
            </ul>
        </div>
        <exx-approved-modal size="md" position="center" :is-visible="openApproved" @confirm="performAction(action)" @cancel="cancelAction"/>
    </div>
</template>

<script>
import axios from "axios";
import {router} from "@inertiajs/vue3";
import Base from "@/mixins/Base.js";
import {
    ChevronDownIcon,
    BoltIcon,
} from '@heroicons/vue/24/solid';

export default {
    mixins: [Base],
    components: {
        ChevronDownIcon,
        BoltIcon
    },
    data() {
        return {
            open: false,
            action: null,
            openApproved: false,
        }
    },
    methods: {
        onClickAway() {
            this.open = false
        },
        unselect() {
            this.open = false
            this[`$${this.prefix}Store`].unSelectAll()
        },
        cacheRequest(name, value) {
            axios.post(this.getResourceUrl('c'), {
                key: name,
                value: value,
            })
        },
        actionWhenResponse() {
            this.emitter.emit('notify', {
                type: 'success',
                message: this.$t('Action handled successfully')
            })

            this.unselect()
            this.cacheRequest('sort', 'id')
            this.cacheRequest('order', 'asc')
            this.cacheRequest('page', '1')

            router.visit(this.getResourceUrl(''))
        },
        deleteResource(array) {
            axios.post(this.getResourceUrl('action/delete'), {array: array})
                .then(response => {
                    this.actionWhenResponse()
                }).catch(errors => {
                this.emitter.emit('load', false)
                this.errors = errors.response.data.errors
            })
        },
        forceResource(array) {
            axios.post(this.getResourceUrl('action/force'), {array: array})
                .then(response => {
                    this.actionWhenResponse()
                }).catch(errors => {
                this.emitter.emit('load', false)
                this.errors = errors.response.data.errors
            })
        },
        restoreResource(array) {
            axios.post(this.getResourceUrl('action/restore'), {array: array})
                .then(response => {
                    this.actionWhenResponse()
                }).catch(errors => {
                this.emitter.emit('load', false)
                this.errors = errors.response.data.errors
            })
        },
        callModal(action) {
            if (this.count) {
                if (this.actions[action]) {
                    this.action = action
                    this.openApproved = true
                }
            }
        },
        performAction(action) {
            this.openApproved = false
            this.emitter.emit('load', true)
            this.actions[action](this[`$${this.prefix}Store`].selected)
            this.action = null
        },
        cancelAction() {
            this.openApproved = false
            this.action = null
        }
    },
    computed: {
        count() {
            return this[`$${this.prefix}Store`].selected.length
        },
        actions() {
            return {
                delete: this.deleteResource,
                force: this.forceResource,
                restore: this.restoreResource,
            }
        }
    }
}
</script>

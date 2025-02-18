<template>
    <div>
        <div class="w-full antialiased">
            <div class="relative">
                <div class="flex flex-col md:flex-row items-center justify-between space-y-3 md:space-y-0 md:space-x-4 p-6">
                    <exx-search/>
                    <div class="w-full md:w-auto flex flex-col md:flex-row space-y-2 md:space-y-0 items-stretch md:items-center justify-end md:space-x-3 flex-shrink-0">
                        <exx-add-button/>
                        <exx-action-dropdown/>
                        <exx-trashed-dropdown/>
                        <exx-sort-dropdown/>
                        <exx-filter-dropdown/>
                        <exx-column-dropdown/>
                    </div>
                </div>
                <exx-database/>
                <div v-if="loading" class="loader flex justify-center items-center absolute left-0 top-0 w-full h-full bg-[#111827] bg-opacity-80">
                    <fwb-spinner color="green" size="12" class="z-50"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue';

export default {
    layout: Layout,
    data() {
        return {
            loading: false
        }
    },
    beforeMount() {
        this.$resourceStore.set(this.$page.props.resource)
    },
    created() {
        this.emitter.on('load', (event) => {
            this.loading = event
        });
    },
    props: {
        resource: {
            type: Array,
            required: true
        },
        title: {
            type: String,
            default: 'Index'
        }
    }
}
</script>

<template>
    <div>
        <div class="w-full antialiased">
            <div class="relative">
                <div class="flex flex-col lg:flex-row items-center justify-between space-y-3 lg:space-y-0 lg:space-x-4 p-6">
                    <exx-search/>
                    <div class="w-full lg:w-auto flex flex-row items-stretch justify-end max-lg:justify-start space-x-3 flex-shrink-0 max-2xl:space-x-2">
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

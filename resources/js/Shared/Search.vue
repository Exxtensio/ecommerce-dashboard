<template>
    <div class="w-full edit-component component search-field max-w-[400px] md:w-1/2">
        <fwb-input
            @input="find"
            id="search"
            placeholder="Search"
            size="sm"
        >
            <template #prefix>
                <MagnifyingGlassIcon class="w-4 h-4 text-gray-500 dark:text-gray-500"/>
            </template>
            <template #suffix>
                <fwb-button
                    class="btn-primary p-3 relative h-[38px] !rounded-tl-none !rounded-bl-none"
                    color="default"
                    size="sm"
                    @click="submit"
                    square
                >
                    <MagnifyingGlassIcon class="w-4 h-4"/>
                </fwb-button>
            </template>
        </fwb-input>
    </div>
</template>

<script>
import axios from "axios";
import Base from "@/mixins/Base.js";
import {
    MagnifyingGlassIcon
} from '@heroicons/vue/24/solid';

export default {
    mixins: [Base],
    components: {
        MagnifyingGlassIcon
    },
    beforeMount() {
        this.$globalStore.setSearch(null)
    },
    methods: {
        find(e) {
            if (e.target.value) {
                this.$globalStore.setSearch(e.target.value)
            } else {
                this.$resourceStore.clearSearchData()
            }
        },
        async submit() {
            if (this.search) {
                await axios.post(this.getResourceUrl('s'), {
                    search: this.search
                }).then((response) => {
                    this.$resourceStore.setSearchData(response.data)
                })
            }
        }
    },
    computed: {
        search() {
            return this.$globalStore.search
        },
    }
}
</script>

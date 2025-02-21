<template>
    <nav class="flex flex-col md:flex-row text-xs justify-between items-start md:items-center space-y-3 md:space-y-0 py-4 px-6" aria-label="Table navigation">
        <div class="inline-flex space-x-2 items-center">
            <div>
                {{ $t('Rows per page') }}
            </div>
            <div class="w-auto">
                <label for="per_page" class="sr-only">{{ $t('Rows per page') }}</label>
                <select
                    id="per_page"
                    v-model="perPage"
                    @change="setPerPage"
                    class="cursor-pointer block py-1 text-center px-2 w-full bg-transparent border-0 border-b theme-brand-border-color appearance-none focus:outline-none focus:ring-0 peer"
                >
                    <option
                        v-for="count in this.perPageList"
                        :selected="perPage === count"
                        :value="count"
                    >{{ count }}
                    </option>
                </select>
            </div>
            <div class="space-x-2">
                <span class="font-semibold">{{ meta.from }}-{{ meta.to }}</span>
                <span>{{ $t('of') }}</span>
                <span class="font-semibold">{{ meta.total }}</span>
            </div>
        </div>
        <ul class="inline-flex items-stretch -space-x-px">
            <li
                v-for="(link, key) in meta.links"
                :key="key"
                :class="{
                    'max-[1280px]:hidden block': isRounded(link.label) !== 'previous' && isRounded(link.label) !== 'next'
                }"
            >
                <div
                    v-if="link.url === null"
                    class="flex select-none items-center justify-center px-2 leading-tight border theme-brand-border-color"
                    :class="{
                        'py-2.5 h-full': isStringLabel(link.label) === 'string',
                        'py-2': isStringLabel(link.label) === 'int',
                        'rounded-l-md': isRounded(link.label) === 'previous',
                        'rounded-r-md': isRounded(link.label) === 'next',
                    }"
                    v-html="resolveLabel(link.label)"
                />
                <button
                    v-else
                    class="flex items-center justify-center leading-tight border theme-brand-border-color"
                    :class="{
                        'py-2.5 h-full': isStringLabel(link.label) === 'string',
                        'py-2.5 px-3 h-[38px]': isStringLabel(link.label) === 'int',
                        'rounded-l-md px-2': isRounded(link.label) === 'previous',
                        'rounded-r-md px-2': isRounded(link.label) === 'next',
                        'z-10 bg-[#f1f5f9] dark:bg-[#1c2e45]': link.active
                    }"
                    @click.prevent="setPage(link.url)"
                    v-html="resolveLabel(link.label)"
                />
            </li>
        </ul>
    </nav>
</template>

<script>
import axios from "axios";
import {router} from "@inertiajs/vue3";
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    props: {
        meta: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            sortBy: this.$page.props.sort,
            orderBy: this.$page.props.order,
            perPage: this.meta.per_page
        }
    },
    methods: {
        isStringLabel(label) {
            if (label === '&laquo; Previous' || label === 'Next &raquo;' || label === '...')
                return 'string'
            return 'int'
        },
        isRounded(label) {
            if (label === '&laquo; Previous')
                return 'previous'
            else if (label === 'Next &raquo;')
                return 'next'
            return null
        },
        resolveLabel(label) {
            if (label === '&laquo; Previous')
                return '<svg class="max-[1280px]:hidden block w-4 h-4" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg><span class="max-[1280px]:block hidden">Previous</span>'
            else if (label === 'Next &raquo;')
                return '<svg class="max-[1280px]:hidden block w-4 h-4" aria-hidden="true" fill="currentColor" viewbox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg><span class="max-[1280px]:block hidden">Next</span>'
            return label
        },
        async setPage(value) {
            const searchURL = new URL(value);

            if (this.sortBy) searchURL.searchParams.append('sort', this.sortBy)
            if (this.orderBy) searchURL.searchParams.append('order', this.orderBy)

            await axios.post(this.getResourceUrl('c'), {
                key: 'page',
                value: searchURL.searchParams.get('page'),
            })

            router.visit(this.getResourceUrl(''));
        },
        async setPerPage() {
            await axios.post(this.getResourceUrl('c'), {
                key: 'per_page',
                value: this.perPage,
            })

            router.visit(this.getResourceUrl(''));
        },
    }
}
</script>

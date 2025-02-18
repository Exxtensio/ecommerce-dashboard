<template>
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700">
        <button @click.prevent="openModal = true" class="flex items-center w-full justify-center h-24 rounded bg-gray-50 dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700">
            <span class="text-2xl text-gray-400 dark:text-gray-500">
                <svg class="w-3.5 h-3.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 18">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 1v16M1 9h16"/>
                </svg>
            </span>
        </button>

        <div tabindex="-1" aria-hidden="true"
             :class="{'fixed': openModal, 'hidden': !openModal}"
             class="flex overflow-y-auto overflow-x-hidden top-0 right-0 left-0 z-[100] justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <!-- Modal content -->
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Add plugin
                        </h3>
                        <button
                            @click.prevent="openModal = false"
                            type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm h-8 w-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        >
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <!-- Modal body -->
                    <div class="p-4 md:p-5">
                        <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Add one of our available plugin.</p>
                        <ul class="my-4 space-y-3">
                            <li>
                                <button
                                    @click.prevent="install('exxtensio/ecommerce-core')"
                                    class="text-left w-full items-center flex p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                    <svg aria-hidden="true" class="h-5" viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg">
                                        <g transform="translate(-.54676 -1.3211)">
                                            <g transform="matrix(1.5127 0 0 1.5127 -33.123 -30.318)" fill="#fff">
                                                <path transform="translate(3.8839 -11.716)"
                                                      d="m55.852 70.309-24.105-0.094625a6.918 6.918 29.571 0 1-5.8976-3.3464l-6.6105-10.966a4.2346 4.2346 96.557 0 1 0.59066-5.1383l14.823-15.244a8.6504 8.6504 157.23 0 1 6.24-2.6198l32.977 0.1457a6.5943 6.5943 28.849 0 1 5.529 3.0457l16.293 25.52a2.8277 2.8277 118.84 0 1-2.3953 4.3493l-23.011-0.09691a7.5892 7.5892 29.262 0 1-6.4239-3.5994l-10.793-17.463a4.1564 4.1564 13.878 0 0-5.6471-1.3952 3.7692 3.7692 100.33 0 0-0.96712 5.3056l11.307 18.177a2.2382 2.2382 119.17 0 1-1.9093 3.4203z"/>
                                                <path transform="rotate(179.82 59.895 66.356)"
                                                      d="m55.852 70.309-24.105-0.094625a6.918 6.918 29.571 0 1-5.8976-3.3464l-6.6105-10.966a4.2346 4.2346 96.557 0 1 0.59066-5.1383l14.823-15.244a8.6504 8.6504 157.23 0 1 6.24-2.6198l32.977 0.1457a6.5943 6.5943 28.849 0 1 5.529 3.0457l16.293 25.52a2.8277 2.8277 118.84 0 1-2.3953 4.3493l-23.011-0.09691a7.5892 7.5892 29.262 0 1-6.4239-3.5994l-10.793-17.463a4.1564 4.1564 13.878 0 0-5.6471-1.3952 3.7692 3.7692 100.33 0 0-0.96712 5.3056l11.307 18.177a2.2382 2.2382 119.17 0 1-1.9093 3.4203z"/>
                                            </g>
                                        </g>
                                    </svg>

                                    <span class="flex-1 ms-3 whitespace-nowrap">Ecommerce</span>
                                </button>
                            </li>
                        </ul>
                        <div>
                            <a href="#" class="inline-flex items-center text-xs font-normal text-gray-500 hover:underline dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 me-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>
                                </svg>
                                Learn more about our plugins.</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="openModal" class="bg-gray-900/50 dark:bg-gray-900/80 fixed inset-0 z-50"></div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout.vue';
import axios from "axios";
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    layout: Layout,
    data() {
        return {
            openModal: false
        }
    },
    mounted() {
        // setTimeout(function () {
        //     this.emitter.emit('notify', {
        //         type: 'info',
        //         message: 'Hello'
        //     })
        // }.bind(this), 2000);
    },
    methods: {
        install(pluginName) {
            axios.post(this.getResourceUrl('install'), {
                name: pluginName
            })
        }
    }
}
</script>

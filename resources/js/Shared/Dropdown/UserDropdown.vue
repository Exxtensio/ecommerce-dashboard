<template>
    <div class="flex items-center ms-3 relative" v-click-away="onClickAway">
        <div>
            <button
                @click.prevent="open = !open"
                type="button"
                class="flex text-sm rounded-full"
                aria-expanded="false"
            >
                <span class="sr-only">Open user menu</span>
                <fwb-avatar
                    :initials="abbr"
                    status-position="bottom-left"
                    status="online"
                    rounded
                />
            </button>
        </div>
        <div
            :class="{'hidden': !open}"
            class="absolute right-0 top-[45px] z-50 list-none theme-brand-dropdown"
        >
            <div class="px-4 py-3 text-sm select-none" role="none">
                <p class="truncate" role="none">{{ auth.name }}</p>
                <p class="font-medium truncate text-gray-400 dark:text-gray-500" role="none">{{ auth.email }}</p>
            </div>
            <ul class="py-1" role="none">
                <li>
                    <button @click.prevent="logout" role="menuitem">
                        Sign out
                    </button>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import axios from "axios";
import {router} from "@inertiajs/vue3";

export default {
    mixins: [Base],
    data() {
        return {
            open: false
        }
    },
    methods: {
        async logout() {
            await axios.post(this.getUrl('logout'))
                .then(() => {
                    router.visit('/login')
                })
        },
        onClickAway() {
            this.open = false
        }
    },
    computed: {
        abbr() {
            return this.auth.name.split(' ').map((w) => w[0].toUpperCase()).join('').substring(0, 2);
        }
    }
}
</script>

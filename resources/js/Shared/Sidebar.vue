<template>
    <aside
        class="text-sm sidebar"
        :class="[minimize ? 'mini' : 'full']"
        aria-label="Sidebar"
    >
        <div class="flex flex-col h-full px-3 pb-4 pt-5 overflow-y-auto theme-brand-bg">
            <div>
                <ul
                    v-for="(group, index) in appMenu"
                    class="space-y-2"
                    :class="{'pt-5 mt-5 border-t theme-brand-border-color': index}"
                >
                    <li v-for="item in group">
                        <a
                            :href="item.href"
                            :class="{'active': prefix === item.prefix}"
                        >
                            <component :is="item.icon"/>
                            <span v-if="!minimize" class="ms-2">{{ $t(item.title) }}</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div v-if="access.warns.length" class="p-4 mt-auto rounded-sm text-white bg-yellow-500 dark:bg-[#414530] dark:text-yellow-200" role="alert">
                <div class="flex items-center mb-2">
                    <span class="font-medium">{{ capitalize(access.type) }} {{ $t('licence') }}</span>
                </div>
                <p class="text-xs leading-5">
                    {{ access.warns[0] }} <a href="//exxtensio.com/dashboard" target="_blank" class="text-white underline">{{ $t('Renew now') }}</a>
                </p>
            </div>
        </div>
    </aside>
</template>

<script>
import {
    ChartPieIcon,
    BeakerIcon,
    GlobeAsiaAustraliaIcon,
    CurrencyDollarIcon,
    UserGroupIcon,
    ShieldCheckIcon,
    UsersIcon,
    TagIcon,
    SquaresPlusIcon,
    SwatchIcon,
    BuildingStorefrontIcon,
    StarIcon,
    ShoppingCartIcon,
    CreditCardIcon
} from '@heroicons/vue/24/solid'
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    components: {
        ChartPieIcon,
        BeakerIcon,
        GlobeAsiaAustraliaIcon,
        CurrencyDollarIcon,
        UserGroupIcon,
        ShieldCheckIcon,
        UsersIcon,
        TagIcon,
        SquaresPlusIcon,
        SwatchIcon,
        BuildingStorefrontIcon,
        StarIcon,
        ShoppingCartIcon,
        CreditCardIcon
    },
    data() {
        return {
            screenWidth: window.innerWidth
        };
    },
    mounted() {
        window.addEventListener("resize", this.updateWidth);
    },
    beforeUnmount() {
        window.removeEventListener("resize", this.updateWidth);
    },
    methods: {
        updateWidth() {
            this.screenWidth = window.innerWidth;
        }
    },
    computed: {
        minimize() {
            return this.$globalStore.menuToggle === 'true' && this.screenWidth >= 768
        },
        access() {
            return this.$globalStore.getAccess()
        }
    }
}
</script>

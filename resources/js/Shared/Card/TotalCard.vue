<template>
    <div class="flex flex-col w-full h-full relative">
        <button class="absolute w-[16px] -top-[16px] -right-[16px]">
            <TrashIcon class="size:4 text-[var(--exx-danger-color)]" @click="$emit('deleteCard')"/>
        </button>
        <div>
            <div class="flex justify-between items-end">
                <div class="h-[36px]">
                    <p class="text-sm">{{ response.title }}</p>
                    <star-rating
                        v-if="response.icon === 'StarIcon'"
                        v-model:rating="response.column"
                        :show-rating="false"
                        read-only
                        star-size="12"
                        active-color="#eab308"
                        inactive-color="transparent"
                        class="h-[10px]"
                    />
                    <p v-else class="text-xs text-gray-400 dark:text-gray-500">
                        {{ response.column }}
                    </p>
                </div>
                <span
                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                    :class="differentClasses"
                >
                    {{ different }}%
                </span>
            </div>
            <p class="mt-4 text-2xl dark:text-white text-gray-700 font-medium">{{ response.current }} </p>
        </div>

        <div class="mt-auto flex justify-between items-end">
            <button class="text-xs hover:underline" @click="$router.visit(response.buttonLink)">{{ response.buttonName }}</button>
            <component :is="response.icon" class="stroke-1 size-8"/>
        </div>
    </div>
</template>

<script>
import {
    BeakerIcon,
    BuildingStorefrontIcon,
    ChartPieIcon,
    CreditCardIcon,
    CurrencyDollarIcon,
    GlobeAsiaAustraliaIcon,
    PlusIcon,
    ShieldCheckIcon,
    ShoppingCartIcon,
    SquaresPlusIcon,
    StarIcon,
    SwatchIcon,
    TagIcon,
    UserGroupIcon,
    UsersIcon,
    TrashIcon
} from '@heroicons/vue/24/outline'

export default {
    props: {
        response: {
            type: Object,
            required: true,
        }
    },
    emits: ['deleteCard'],
    components: {
        PlusIcon,
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
        CreditCardIcon,
        TrashIcon
    },
    computed: {
        different() {
            if (this.response.past === 0) return this.response.current > 0 ? 100 : 0;
            return Math.round(((this.response.current - this.response.past) / this.response.past) * 100);
        },
        differentClasses() {
            return this.different >= 0 ? ['bg-green-500/10', 'text-green-400', 'ring-green-500/20'] : ['bg-red-400/10', 'text-red-400', 'ring-red-400/10']
        }
    }
}
</script>

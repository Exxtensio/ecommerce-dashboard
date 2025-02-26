<template>
    <a :href="appUrl" :class="{'flex w-[52px] ms-2': minimize, [classes]: !minimize}">
        <svg
            viewBox="0 0 120 120"
            xmlns="http://www.w3.org/2000/svg"
            class="fill-[#1c1d1f] dark:fill-white"
            :class="{
                'ml-1': minimize,
                'h-9 w-9': isAuth,
                'h-7 w-7 me-3': !isAuth,
            }"
        >
            <g transform="translate(-.54676 -1.3211)">
                <g transform="matrix(1.5127 0 0 1.5127 -33.123 -30.318)">
                    <path transform="translate(3.8839 -11.716)"
                          d="m55.852 70.309-24.105-0.094625a6.918 6.918 29.571 0 1-5.8976-3.3464l-6.6105-10.966a4.2346 4.2346 96.557 0 1 0.59066-5.1383l14.823-15.244a8.6504 8.6504 157.23 0 1 6.24-2.6198l32.977 0.1457a6.5943 6.5943 28.849 0 1 5.529 3.0457l16.293 25.52a2.8277 2.8277 118.84 0 1-2.3953 4.3493l-23.011-0.09691a7.5892 7.5892 29.262 0 1-6.4239-3.5994l-10.793-17.463a4.1564 4.1564 13.878 0 0-5.6471-1.3952 3.7692 3.7692 100.33 0 0-0.96712 5.3056l11.307 18.177a2.2382 2.2382 119.17 0 1-1.9093 3.4203z"/>
                    <path transform="rotate(179.82 59.895 66.356)"
                          d="m55.852 70.309-24.105-0.094625a6.918 6.918 29.571 0 1-5.8976-3.3464l-6.6105-10.966a4.2346 4.2346 96.557 0 1 0.59066-5.1383l14.823-15.244a8.6504 8.6504 157.23 0 1 6.24-2.6198l32.977 0.1457a6.5943 6.5943 28.849 0 1 5.529 3.0457l16.293 25.52a2.8277 2.8277 118.84 0 1-2.3953 4.3493l-23.011-0.09691a7.5892 7.5892 29.262 0 1-6.4239-3.5994l-10.793-17.463a4.1564 4.1564 13.878 0 0-5.6471-1.3952 3.7692 3.7692 100.33 0 0-0.96712 5.3056l11.307 18.177a2.2382 2.2382 119.17 0 1-1.9093 3.4203z"/>
                </g>
            </g>
        </svg>

        <span v-if="!isAuth && !minimize" class="self-center text-[18px] font-semibold whitespace-nowrap text-[#1c1d1f] dark:text-white truncate">
            {{ appName }}
        </span>
    </a>
</template>

<script>
import Base from '@/mixins/Base.js'

export default {
    mixins: [Base],
    props: {
        classes: {
            type: String,
            default: 'flex w-[234px] ms-2.5'
        },
        auth: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        isAuth() {
            return this.auth
        },
        minimize() {
            return this.$globalStore.menuToggle === 'true'
        }
    }
}
</script>

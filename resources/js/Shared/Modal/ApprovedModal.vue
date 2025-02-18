<template>
    <fwb-modal v-if="isVisible" position="top-center" persistent class="approved-modal z-50">
        <template #body>
            <div class="text-lg text-gray-900 dark:text-white text-center flex flex-col justify-center items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-[50px] h-[50px] mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z"/>
                </svg>
                <span>{{ message }}</span>
            </div>
        </template>
        <template #footer>
            <div class="flex justify-center space-x-4">
                <fwb-button size="md" @click="confirmAction" color="red">
                    Yes, I'm sure
                </fwb-button>
                <fwb-button size="md" @click="cancelAction" color="alternative">
                    No
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>

<script>
export default {
    emits: ['confirm', 'cancel'],
    props: {
        isVisible: {
            type: Boolean,
            required: true,
        },
        message: {
            type: String,
            default: 'Are you sure you want to handle this action?',
        },
    },
    methods: {
        confirmAction() {
            this.$emit('confirm');
        },
        cancelAction() {
            this.$emit('cancel');
        },
    }
}
</script>

<template>
    <fwb-modal v-if="isVisible" position="top-center" persistent class="approved-modal z-50">
        <template #body>
            <div class="font-medium text-center flex flex-col justify-center items-center">
                <QuestionMarkCircleIcon class="w-[40px] h-[40px] mb-4 stroke-1"/>
                <span>{{ $t(message) }}</span>
            </div>
        </template>
        <template #footer>
            <div class="flex justify-center space-x-4">
                <fwb-button class="btn-dander" @click="confirmAction" color="red">
                    {{ $t('Yes, I\'m sure') }}
                </fwb-button>
                <fwb-button class="btn-light" @click="cancelAction" color="light">
                    {{ $t('No') }}
                </fwb-button>
            </div>
        </template>
    </fwb-modal>
</template>

<script>
import {
    QuestionMarkCircleIcon
} from '@heroicons/vue/24/outline';
export default {
    emits: ['confirm', 'cancel'],
    components: {
        QuestionMarkCircleIcon
    },
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

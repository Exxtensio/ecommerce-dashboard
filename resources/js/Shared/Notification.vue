<template>
    <Transition :name="position === 'bottom-center' ? 'bottom-fade' : 'top-fade'">
        <div v-show="show"
             class="notification flex flex-col p-4 fixed left-1/2 min-w-[280px] max-w-[600px] rounded-md z-[100] transform -translate-x-1/2"
             :class="[types[type].div, {
                 'bottom-5': position === 'bottom-center',
                 'top-5': position === 'top-center'
             }]"
             role="alert"
        >
            <div class="flex">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                    :class="type === 'text' ? ['dark:text-gray-300'] : []"
                    fill="currentColor"
                    class="flex-shrink-0 w-5 h-5 mr-3 relative"
                >
                    <path
                        v-if="type === 'info'"
                        fill-rule="evenodd"
                        d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                        clip-rule="evenodd"
                    />

                    <path
                        v-else-if="type === 'danger'"
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                        clip-rule="evenodd"
                    />

                    <path
                        v-else-if="type === 'warning'"
                        fill-rule="evenodd"
                        d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                        clip-rule="evenodd"
                    />

                    <path
                        v-else-if="type === 'success'"
                        fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                        clip-rule="evenodd"
                    />

                </svg>
                <span class="sr-only">{{ type.toUpperCase() }}</span>
                <div
                    class="message-block font-medium select-none text-sm mr-7"
                    :class="type === 'text' ? ['text-gray-800', 'dark:text-gray-300'] : []"
                    v-html="!Array.isArray(message) ? message : 'Attention needed'"
                ></div>

                <button
                    @click.prevent="show = false"
                    type="button"
                    class="ms-auto inline-flex absolute right-[4px] top-[4px] items-center justify-center h-8 w-8"
                    :class="[types[type].button]"
                    aria-label="Close"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
            </div>
            <div v-if="Array.isArray(message)" class="pr-[10px]">
                <ul class="list-disc font-medium text-sm pl-[47px] mt-[10px]">
                    <li v-for="str in message">{{ str }}</li>
                </ul>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    data() {
        return {
            types: {
                info: {
                    div: ['text-white', 'bg-blue-500', 'dark:bg-[#223d5e]', 'dark:text-blue-400'],
                    button: ['text-white', 'dark:text-blue-400']
                },
                danger: {
                    div: ['text-white', 'bg-red-400', 'dark:bg-[#413242]', 'dark:text-red-400'],
                    button: ['text-white', 'dark:text-red-400']
                },
                warning: {
                    div: ['text-white', 'bg-yellow-500', 'dark:bg-[#414530]', 'dark:text-yellow-200'],
                    button: ['text-white', 'dark:text-yellow-200']
                },
                success: {
                    div: ['text-white', 'bg-[#249782]', 'dark:bg-[#249782]', 'dark:text-white'],
                    button: ['text-white', 'dark:text-white']
                }
            },
            position: 'bottom-center',
            type: 'info',
            message: this.$t('I have nothing to tell you'),
            show: false,
            timer: null,
            secondsLeft: null,
            defaultLeft: 5,
            isRunning: false,
        }
    },
    beforeUnmount() {
        if (this.timer) clearInterval(this.timer);
    },
    methods: {
        startTimer() {
            this.isRunning = true;
            this.secondsLeft = !this.secondsLeft ? this.defaultLeft : this.secondsLeft;

            this.timer = setInterval(() => {
                if (this.secondsLeft > 0) {
                    this.secondsLeft -= 1;
                } else {
                    clearInterval(this.timer);
                    this.isRunning = false;
                    this.onTimerEnd()
                }
            }, 1000);
        },
        onTimerEnd() {
            this.show = false
            setTimeout(() => {
                this.type = 'info'
                this.message = this.$t('I have nothing to tell you')
            }, 300)
        },
    },
    created() {
        this.emitter.on('notify', (event) => {
            this.show = false
            if (this.timer) clearInterval(this.timer)

            setTimeout(() => {
                const allow = event.type &&
                    ['info', 'danger', 'success', 'warning'].includes(event.type) &&
                    event.message

                if (allow) {
                    if(event.position) this.position = event.position
                    this.type = event.type
                    this.message = event.message
                    this.secondsLeft = event.duration || this.defaultLeft
                    this.show = true
                    this.startTimer()
                }
            }, 300)
        });
    }
}
</script>

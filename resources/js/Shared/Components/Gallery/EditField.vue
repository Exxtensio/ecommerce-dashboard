<template>
    <div :class="{'required': isRequired, 'error': localErrorMessage}">
        <div>
            <fwb-file-input
                v-model="field.files"
                :label="field.name"
                :class="{'file-error': localErrorMessage}"
                size="sm"
                @change="onChange($event.target.files)"
                multiple
            />
        </div>
        <div v-if="field.resolvedForDisplay.length" class="grid gap-2 grid-cols-3 mt-4">
            <div v-for="(file, index) in field.resolvedForDisplay.filter(f => !f.deleted)" class="group relative">
                <fwb-button
                    :color="file.default ? 'default' : 'green'"
                    pill
                    size="xs"
                    @click="makeFileDefault(index)"
                    class="absolute left-2 top-2 text-[10px]"
                >
                    {{ file.default ? $t('Default') : $t('Make as Default') }}
                </fwb-button>
                <button
                    v-if="!file.default"
                    @click="file.deleted = true"
                    class="absolute right-2 top-2"
                >
                    <MinusCircleIcon class="size-6 text-red-600 dark:text-red-400"/>
                </button>
                <fwb-img
                    img-class="rounded-md border theme-brand-border-color h-[150px] w-full object-contain"
                    :src="file.src || file.file"
                />
            </div>
        </div>
        <exx-help-text v-if="!localErrorMessage" :text="field.helpText" type="text"/>
        <exx-help-validation :text="localErrorMessage" type="text"/>
    </div>
</template>

<script>
import {
    MinusCircleIcon
} from '@heroicons/vue/24/solid'
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    components: {
        MinusCircleIcon
    },
    props: {
        field: {
            type: Object,
            required: true
        },
        errors: {
            type: Object,
            required: true
        }
    },
    data() {
        return {
            localErrorMessage: null,
        };
    },
    created() {
        if (!this.field.resolvedForDisplay) {
            this.field.resolvedForDisplay = []
        } else {
            this.field.resolvedForDisplay.forEach(item => {
                item.file = null
                item.deleted = false
            })
        }
    },
    methods: {
        makeFileDefault(index) {
            this.field.resolvedForDisplay.forEach((item, i) => {
                item.default = i === index ? 1 : 0
            })

            this.field.resolvedForDisplay.sort((a, b) => b.default - a.default)
        },
        onChange(files) {
            const hasDefault = this.field.resolvedForDisplay.some(item => item.default === 1)
            if (files && files instanceof FileList) {
                Array.from(files).forEach((f, index) => {
                    if (f && f instanceof File) {
                        const _id = crypto.randomUUID()
                        f.id = _id
                        const reader = new FileReader()
                        reader.onload = (e) => {
                            this.field.resolvedForDisplay.push({
                                default: !hasDefault && index === 0 ? 1 : 0,
                                file: e.target.result,
                                src: null,
                                id: _id,
                                deleted: false
                            })
                        };
                        reader.readAsDataURL(f)
                    }
                })

            }
        },
    },
    watch: {
        errors: {
            deep: true,
            immediate: true,
            handler(newErrors) {
                this.localErrorMessage = newErrors && newErrors.hasOwnProperty(this.field.attribute)
                    ? newErrors[this.field.attribute][0]
                    : null
            },
        },
    },
}
</script>

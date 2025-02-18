<template>
    <div :class="{'required': isRequired, 'error': localErrorMessage}">
        <fwb-file-input
            v-model="field.file"
            :label="field.name"
            size="sm"
            @change="onFileChange($event.target.files[0])"
        />
        <exx-help-text v-if="!localErrorMessage" :text="field.helpText" type="text"/>
        <exx-help-validation :text="localErrorMessage" type="text"/>

        <div v-if="field.resolvedForDisplay" class="grid gap-2 grid-cols-3 mt-4">
            <div class="group relative">
                <button @click="deleteImage" class="absolute right-2 top-2">
                    <MinusCircleIcon class="size-6 text-red-600 dark:text-red-400"/>
                </button>
                <fwb-img img-class="rounded-md border theme-brand-border-color" :src="field.resolvedForDisplay"/>
            </div>
        </div>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import {MinusCircleIcon} from "@heroicons/vue/24/solid/index.js";
import axios from "axios";

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
    methods: {
        deleteImage() {
            const fileInput = document.querySelector('input[type="file"]')
            if (this.field.file instanceof File) {
                this.field.file = null
                this.field.resolvedForDisplay = null
                fileInput.value = null
            } else {
                axios.post(this.getResourceUrl('action/delete-image'), {
                    src: this.field.resolved,
                    disk: this.field.disk
                }).then(response => {
                    this.field.file = null
                    this.field.resolvedForDisplay = null
                    fileInput.value = null
                })
            }
        },
        onFileChange(file) {
            if (file && file instanceof File) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.field.resolvedForDisplay = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.field.resolvedForDisplay = null;
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

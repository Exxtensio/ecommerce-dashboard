<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly}">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
        <multiselect
            :modelValue="modelValue"
            @update:modelValue="update"
            :options="field.options"
            :searchable="field.searchable"
            :close-on-select="true"
            :allow-empty="field.nullable || false"
            :show-labels="true"
            class="w-full"
            track-by="value"
            label="name"
            :placeholder="field.placeholder"
            :disabled="field.readonly"
        >
            <template #caret>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="absolute right-0 top-0 p-[12px] size-[40px] dark:text-gray-400 text-gray-700">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </template>
        </multiselect>
        <exx-help-text v-if="!localErrorMessage" :text="field.helpText" type="text"/>
        <exx-help-validation :text="localErrorMessage" type="text"/>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import Depend from "@/mixins/Depend.js";

export default {
    mixins: [Base, Depend],
    props: {
        field: {
            type: Object,
            required: true
        },
        dependOnComponent: {
            type: Object,
            default: null
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
        if (this.field.dependOn) {
            if (this.dependOnComponent) {
                this.setDependencyVariables(this.field, this.dependOnComponent.resolvedForUpdate)
                this.field.resolvedForUpdate = this.field.value || this.field.default
            }

            this.emitter.on(`dependency-${this.field.component}-${this.field.dependOn}`, (event) => {
                this.setDependencyVariables(this.field, event)
                this.field.resolvedForUpdate = this.field.default
            });
        }
    },
    methods: {
        update(value) {
            this.field.resolvedForUpdate = value ? value.value : null
        }
    },
    watch: {
        field: {
            deep: true,
            immediate: true,
            handler(field) {
                this.emitter.emit(`dependency-${this.field.component}-${field.attribute}`, field.resolvedForUpdate)
            },
        },
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
    computed: {
        modelValue() {
            return this.field.options.find(o => o.value === this.field.resolvedForUpdate) || null
        }
    }
}
</script>

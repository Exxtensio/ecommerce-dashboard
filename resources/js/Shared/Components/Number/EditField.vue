<template>
    <div :class="{'required': isRequired, 'error': localErrorMessage}">
        <fwb-input
            ref="input"
            v-model="field.resolvedForUpdate"
            :label="field.name"
            size="sm"
            @input="validateLazyInput($event)"
            :validation-status="localErrorMessage ? 'error' : null"
        >
            <template #prefix>
                <fwb-button
                    class="btn-light p-3 relative !size-[40px] !rounded-tr-none !rounded-br-none flex justify-center items-center"
                    color="light"
                    size="sm"
                    @click="decrease"
                    square
                >
                    <MinusIcon class="size-3 font-medium"/>
                </fwb-button>
            </template>
            <template #suffix>
                <fwb-button
                    class="btn-light p-3 relative !size-[40px] !rounded-tl-none !rounded-bl-none flex justify-center items-center"
                    color="light"
                    size="sm"
                    @click="increase"
                    square
                >
                    <PlusIcon class="size-3 font-medium"/>
                </fwb-button>
            </template>

            <template v-if="!localErrorMessage && field.helpText" #helper>
                <exx-help-text :text="field.helpText"/>
            </template>
            <template v-if="localErrorMessage" #validationMessage>
                <exx-help-validation :text="localErrorMessage"/>
            </template>
        </fwb-input>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";
import {
    MinusIcon,
    PlusIcon
} from '@heroicons/vue/24/solid'

export default {
    mixins: [Base],
    components: {
        MinusIcon,
        PlusIcon
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
        if (this.field.resolvedForUpdate)
            this.field.resolvedForUpdate = parseFloat(this.field.resolvedForUpdate).toFixed(this.field.places);
    },
    methods: {
        validateLazyInput(e) {
            let sanitizedValue = this.field.resolvedForUpdate
            if (!this.field.places) {
                sanitizedValue = typeof this.field.resolvedForUpdate !== 'string'
                    ? this.field.resolvedForUpdate.toString().replace(/[^0-9]/g, '')
                    : this.field.resolvedForUpdate.replace(/[^0-9]/g, '')
            } else {
                sanitizedValue = typeof this.field.resolvedForUpdate !== 'string'
                    ? this.field.resolvedForUpdate.toString().replace(/[^0-9.]/g, '')
                    : this.field.resolvedForUpdate.replace(/[^0-9.]/g, '')
            }
            const parts = sanitizedValue.split('.')

            if (parts.length === 2 && parts[1] > this.field.places) {
                this.field.resolvedForUpdate = parts[0] + '.' + parts[1].slice(0, this.field.places);
            } else {
                this.field.resolvedForUpdate = sanitizedValue;
            }

            if (this.field.places && this.field.resolvedForUpdate)
                this.field.resolvedForUpdate = parseFloat(this.field.resolvedForUpdate).toFixed(this.field.places)

            this.limiterMax()
            this.limiterMin()

            if(!e.target.value)
                this.field.resolvedForUpdate = e.target.value
        },
        limiterMin() {
            if (parseFloat(this.field.resolvedForUpdate) < parseFloat(this.field.min) || (!this.field.resolvedForUpdate.length && !this.field.nullable))
                this.field.resolvedForUpdate = !this.field.places ? Math.trunc(this.field.min) : parseFloat(this.field.min).toFixed(this.field.places)
        },
        limiterMax() {
            if (parseFloat(this.field.resolvedForUpdate) > parseFloat(this.field.max))
                this.field.resolvedForUpdate = !this.field.places ? Math.trunc(this.field.max) : parseFloat(this.field.max).toFixed(this.field.places)
        },
        increase() {
            if (!this.field.resolvedForUpdate)
                this.field.resolvedForUpdate = !this.field.places ? 0 : parseFloat('0').toFixed(this.field.places);

            if (!this.field.places) {
                this.field.resolvedForUpdate++
            } else {
                this.field.resolvedForUpdate = (parseFloat(this.field.resolvedForUpdate || '0') + parseFloat(this.field.step)).toFixed(this.field.places);
            }
            this.limiterMax()
        },
        decrease() {
            if (!this.field.resolvedForUpdate)
                this.field.resolvedForUpdate = !this.field.places ? 0 : parseFloat('0').toFixed(this.field.places);

            if (!this.field.places) {
                this.field.resolvedForUpdate--
            } else {
                this.field.resolvedForUpdate = (parseFloat(this.field.resolvedForUpdate) - parseFloat(this.field.step)).toFixed(this.field.places);
            }
            this.limiterMin()
        }
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

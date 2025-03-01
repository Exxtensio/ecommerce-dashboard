<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly, 'error': localErrorMessage}">
        <fwb-input
            v-model="field.resolvedForUpdate"
            :label="field.name"
            :placeholder="field.placeholder"
            size="sm"
            :disabled="field.readonly"
            :validation-status="localErrorMessage ? 'error' : null"
        >
            <template #suffix>
                <fwb-button
                    v-if="field.readonly"
                    class="font-medium px-4 relative btn-primary"
                    color="default"
                    size="xs"
                    @click="field.readonly = false"
                    square
                >
                    {{ $t('Edit') }}
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
import slugify from 'slugify'
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
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
            edit: false,
        };
    },
    created() {
        this.emitter.on('slugify', (event) => {
            this.field.resolvedForUpdate = slugify(event, {
                replacement: '-',
                remove: undefined,
                lower: true,
                strict: true,
                locale: 'en',
                trim: true
            })
        });
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
    }
}
</script>

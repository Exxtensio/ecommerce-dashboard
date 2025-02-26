<template>
    <div class="readonly" :class="{'required': isRequired}">
        <fwb-select
            :model-value="field.value"
            :label="field.name"
            :options="field.options"
            size="sm"
            class="w-full"
            :disabled="true"
        >
            <template v-if="field.helpText" #helper>
                <exx-help-text :text="field.helpText"/>
            </template>
        </fwb-select>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    created() {
        if (!this.field.options.length && this.relations.hasOwnProperty(this.field.relation.prefix))
            this.field.options = this.relations[this.field.relation.prefix]
    },
    computed: {
        relations() {
            return this.$page.props.resource.data.relations
        }
    }
}
</script>

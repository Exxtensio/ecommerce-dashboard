<template>
    <td>
        <div>{{ selected }}</div>
    </td>
</template>

<script>
import Depend from "@/mixins/Depend.js";

export default {
    mixins: [Depend],
    props: {
        field: {
            type: Object,
            required: true
        },
        dependOnComponent: {
            type: Object,
            default: false
        },
    },
    created() {
        if (this.field.dependOn && this.dependOnComponent)
            this.setDependencyVariables(this.field, this.dependOnComponent.value)
    },
    computed: {
        selected() {
            if (this.field.selected.length) return this.field.selected
            const result = this.field.options.filter(o => o.value === this.field.value)
            if (result.length)
                return this.field.options.filter(o => o.value === this.field.value)[0].name
            return ''
        }
    }
}
</script>

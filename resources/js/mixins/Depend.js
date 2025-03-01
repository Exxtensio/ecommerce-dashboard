export default {
    methods: {
        dependComponent(fields, attribute) {
            return this.$_.first(fields.filter(i => i.attribute === attribute))
        },
        setDependencyVariables(field, event) {
            Object.entries(field.dependOnOptions).map(function ([key, value]) {
                field[key] = value[event]
            }.bind(this))
        }
    }
}

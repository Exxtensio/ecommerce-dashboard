const fields = import.meta.glob('./Shared/Components/**/*.vue', {eager: true});

const registeredFields = {};

Object.entries(fields).forEach(([path, field]) => {
    const segments = path.split('/');
    if (segments.length === 5) {
        let name = `${segments[3]}${segments[4].replace('.vue', '')}`

        name = name.replace(/([a-z0-9])([A-Z])/g, '$1-$2')
            .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
            .toLowerCase();

        registeredFields[name] = field.default || field;
    }
});

export default registeredFields;

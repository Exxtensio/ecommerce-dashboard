const filters = import.meta.glob('./Shared/Filters/*.vue', {eager: true});

const registeredFilters = {};

Object.entries(filters).forEach(([path, filter]) => {
    const segments = path.split('/');
    if (segments.length === 4) {
        let name = `${segments[3].replace('.vue', '')}`
        name = name.replace(/([a-z0-9])([A-Z])/g, '$1-$2')
            .replace(/([A-Z]+)([A-Z][a-z])/g, '$1-$2')
            .toLowerCase();

        registeredFilters[name] = filter.default || filter;
    }
});

export default registeredFilters;

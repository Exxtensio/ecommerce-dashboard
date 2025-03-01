export default {
    computed: {
        prefix() {
            if (
                !this.$page.props.hasOwnProperty('resource') ||
                !this.$page.props.resource.hasOwnProperty('data') ||
                !this.$page.props.resource.data.hasOwnProperty('prefix')
            ) return '';
            return this.$page.props.resource.data.prefix
        },
        singularLabel() {
            return this.$page.props.resource.data.singularLabel
        },
        label() {
            return this.$page.props.resource.data.label
        },
        perPageList() {
            return this.$page.props.resource.data.perPage
        },
        hasSoftDelete() {
            return this.$page.props.resource.data.hasSoftDelete
        },
        canCreate() {
            return this.$page.props.resource.data.canCreate
        },
        appName() {
            return this.$page.props.appName
        },
        appUrl() {
            return this.$page.props.appUrl
        },
        appAdminUrl() {
            return this.$page.props.appAdminUrl
        },
        appMenu() {
            return this.$page.props.appMenu
        },
        appPanels() {
            return this.$page.props.appPanels
        },
        auth() {
            return this.$page.props.auth
        },
        theme() {
            return localStorage.getItem('sellexx_theme_type')
        },
        pageType() {
            const split = window.location.pathname.split('/')
            if (split[split.length - 1] === 'edit') {
                return 'edit'
            } else if (split[split.length - 1] === 'create') {
                return 'create'
            }
            return 'show'
        },
        isRequired() {
            let rules = this.field.rules
            if (this.pageType === 'edit') {
                rules.concat(this.field.updateRules)
            } else if (this.pageType === 'create') {
                rules.concat(this.field.creationRules)
            }
            return rules.includes('required')
        }
    },
    methods: {
        can(id, permission) {
            if(this.$resourceStore.searchEnabled) {
                if(!this.$resourceStore.searchData.length || !Object.keys(this.$resourceStore.searchPermissions).length || !this.$resourceStore.searchPermissions.hasOwnProperty('item-' + id))
                    return false;
                return this.$resourceStore.searchPermissions['item-' + id][permission] || false;
            } else {
                if(!this.$resourceStore.data.length || !Object.keys(this.$resourceStore.permissions).length || !this.$resourceStore.permissions.hasOwnProperty('item-' + id))
                    return false;
                return this.$resourceStore.permissions['item-' + id][permission] || false;
            }
        },
        normalizeUrl(inputUrl) {
            try {
                const url = new URL(inputUrl);
                url.pathname = url.pathname
                    .split('/')
                    .filter(segment => segment.trim() !== '')
                    .join('/');

                return url.toString();
            } catch (error) {
                return null;
            }
        },
        capitalize(str) {
            if (!str) return '';
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        toTitleCase(str) {
            return str
                .replace(/([a-z])([A-Z])/g, '$1 $2')
                .replace(/^./, (char) => char.toUpperCase());
        },
        groupBy(array, includes) {
            return array.reduce((result, item) => {
                const group = item.group
                if (!result[group]) result[group] = []
                item.checked = includes.includes(item.value)
                result[group].push(item)
                return result
            }, {})
        },
        resolveComponent(name, type) {
            return name.replace('-field', `-${type}-field`);
        },
        getUrl(path) {
            return this.normalizeUrl(`${this.appUrl}/${path}`)
        },
        getAdminUrl(path) {
            return this.normalizeUrl(`${this.appAdminUrl}/${path}`)
        },
        getResourceUrl(path) {
            return this.normalizeUrl(`${this.appAdminUrl}/${this.prefix}/${path}`)
        },
        getCorrectInterpolation(path) {
            if (path === 'валюта') return 'валюту';
            if (path === 'категория') return 'категорию';
            if (path === 'пользователь') return 'пользователя';
            if (path === 'користувач') return 'користувача';
            if (path === 'категорія') return 'категорію';
            return path;
        }
    }
}

import {defineStore} from 'pinia';

export const useResourceStore = defineStore('sellexx_resource', {
    state: () => ({
        searchEnabledBoolean: false,
        searchDataArray: [],
        searchPermissionsObject: {},
        searchMetaObject: {},
        dataArray: [],
        filtersArray: [],
        columnsArray: [],
        permissionsObject: {},
        metaObject: {},
    }),
    persist: true,

    getters: {
        searchEnabled(state) {
            return state.searchEnabledBoolean;
        },
        searchData(state) {
            return state.searchDataArray;
        },
        searchPermissions(state) {
            return state.searchPermissionsObject;
        },
        searchMeta(state) {
            return state.searchMetaObject;
        },
        data(state) {
            return state.dataArray;
        },
        filters(state) {
            return state.filtersArray;
        },
        columns(state) {
            return state.columnsArray;
        },
        permissions(state) {
            return state.permissionsObject;
        },
        meta(state) {
            return state.metaObject;
        }
    },

    actions: {
        set(object) {
            this.clearSearchData()

            this.dataArray = []
            this.filtersArray = []
            this.columnsArray = []
            this.permissionsObject = {}
            this.metaObject = {}

            this.dataArray = object.data.fields
            this.filtersArray = object.data.filters
            this.columnsArray = object.data.columns
            this.permissionsObject = object.data.permissions
            this.metaObject = object.meta || {}
        },
        setSearchData(data) {
            this.searchDataArray = []
            this.searchDataArray = data.data.fields

            this.searchPermissionsObject = {}
            this.searchPermissionsObject = data.data.permissions

            this.searchMetaObject = {}
            this.searchMetaObject = data.meta

            this.searchEnabledBoolean = true
        },
        clearSearchData() {
            this.searchEnabledBoolean = false
            this.searchDataArray = []
            this.searchPermissionsObject = {}
            this.searchMetaObject = {}
        }
    },
});

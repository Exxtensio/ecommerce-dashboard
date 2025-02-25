import {defineStore} from 'pinia';
import _ from 'underscore';

export const createStore = (name) => {
    return defineStore(name, {
        state: () => ({
            selectedArray: []
        }),
        persist: true,

        getters: {
            selected(state) {
                return state.selectedArray
            }
        },

        actions: {
            setSelected(id) {
                const toAdd = _.difference(id, this.selectedArray)
                toAdd.forEach(item => {
                    this.selectedArray.push(item)
                });
            },
            removeSelected(id) {
                this.selectedArray = this.selected.filter(item => !id.includes(item))
            },
            unSelectAll() {
                this.selectedArray = []
            }
        },
    })
}

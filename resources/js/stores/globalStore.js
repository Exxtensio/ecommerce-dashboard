import {defineStore} from 'pinia';
import CryptoJS from 'crypto-js';
import axios from "axios";

const currentTimestamp = Math.floor(Date.now() / 1000)

export const useGlobalStore = defineStore('sellexx_global', {
    state: () => ({
        appKeyString: null,
        searchString: null,
        menuToggleBoolean: 'false',
        errorsArray: [],
        warnsArray: []
    }),
    persist: true,

    getters: {
        search(state) {
            return state.searchString
        },
        appKey(state) {
            return state.appKeyString
        },
        errors(state) {
            return state.errorsArray
        },
        warns(state) {
            return state.warnsArray
        },
        menuToggle(state) {
            return state.menuToggleBoolean
        }
    },

    actions: {
        setSearch(string) {
            this.searchString = string
        },
        setAppKey(string) {
            this.appKeyString = string
        },
        setErrors(errors) {
            this.errorsArray = [...new Set(errors)]
        },
        setWarns(warns) {
            this.warnsArray = [...new Set(warns)]
        },
        changeMenuToggle() {
            this.menuToggleBoolean = this.menuToggleBoolean === 'false' ? 'true' : 'false'
        },
        decryptData(data, key) {
            let bytes = CryptoJS.AES.decrypt(data, key);
            return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
        },
        getAccess() {
            const realData = this.decryptData(localStorage.getItem('sellexx_verification'), this.appKey)
            return {
                type: realData.type,
                errors: this.errors,
                warns: this.warns,
            }
        },
        verifyKey(key, secret) {
            this.setAppKey(key)

            this.setErrors([])
            this.setWarns([])

            let errors = [];
            let warns = [];
            if(!secret || !key) {
                if(!key) errors.push(`SELLEXX_DASHBOARD_KEY was not found in the .env file.`)
                if(!secret) errors.push(`SELLEXX_DASHBOARD_SECRET was not found in the .env file.`)
                this.setErrors(errors)
            } else {
                const realData = this.decryptData(secret, key)
                if(!localStorage.getItem('sellexx_verification') || localStorage.getItem('sellexx_verification') !== secret) {
                    axios.post(`https://exxtensio.com/api/licence/${key}`, {}, {headers: {'X-Api-Key': 'YCwWdyr7JNfZM92mh4zQGxDsVFHk6n5aebpEvB8LA3RucKUtPT'}})
                        .then((response) => {
                            const responseData = JSON.parse(atob(response.data.key))

                            if(window.location.origin !== realData.host || window.location.origin !== responseData.host || realData.host !== responseData.host)
                                errors.push(`The used host ${window.location.origin} does not match the expected host ${responseData.host}.`)

                            if(realData.type !== responseData.type)
                                errors.push(`The type of the used license is incorrect.`)

                            if(currentTimestamp > realData.expired_at || currentTimestamp > responseData.expired_at)
                                errors.push(`Your license has expired.`)

                            this.setWarns(warns)
                            this.setErrors(errors)

                            localStorage.setItem('sellexx_verification', secret)
                        })
                }

                let left = realData.expired_at - currentTimestamp
                if(left === 0 || left < 0) {
                    errors.push(`Your license has expired.`)
                    this.setErrors(errors)
                } else {
                    let daysLeft = Math.round(left / 86400)
                    if(daysLeft < 2) {
                        warns.push(`Your license will expire in less than 1 day.`)
                    } else if (daysLeft < 4) {
                        warns.push(`Your license will expire in less than 3 days.`)
                    } else if (daysLeft < 8) {
                        warns.push(`Your license will expire in less than 7 days.`)
                    }
                    this.setWarns(warns)
                }
            }
        }
    },
});

import {defineStore} from 'pinia';
import CryptoJS from 'crypto-js';

const currentTimestamp = Math.floor(Date.now() / 1000)

export const useGlobalStore = defineStore('sellexx_global', {
    state: () => ({
        appKeyString: null,
        appSecretString: null,
        searchString: null,
        menuToggleBoolean: 'false',
        errorsArray: [],
        warnsArray: [],
        accessObject: null
    }),
    persist: true,

    getters: {
        search(state) {
            return state.searchString
        },
        appKey(state) {
            return state.appKeyString
        },
        appSecret(state) {
            return state.appSecretString
        },
        access(state) {
            return state.accessObject
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
        setAppKey(key, secret) {
            this.accessObject = null
            this.appKeyString = key
            this.appSecretString = secret
        },
        clearErrors() {
            this.errorsArray = []
        },
        clearWarns() {
            this.warnsArray = []
        },
        pushError(error) {
            this.errorsArray.push(error)
        },
        pushWarn(warn) {
            this.warnsArray.push(warn)
        },
        changeMenuToggle() {
            this.menuToggleBoolean = this.menuToggleBoolean === 'false' ? 'true' : 'false'
        },
        encryptTimestamp(timestamp) {
            return CryptoJS.AES.encrypt(timestamp.toString(), this.appKey).toString()
        },
        decryptTimestamp() {
            try {
                let bytes = CryptoJS.AES.decrypt(localStorage.getItem('sellexx_verification_timestamp'), this.appKey);
                return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
            } catch (error) {
                return null;
            }
        },
        decryptData(key, secret) {
            try {
                let bytes = CryptoJS.AES.decrypt(secret, key);
                return JSON.parse(bytes.toString(CryptoJS.enc.Utf8));
            } catch (error) {
                return null;
            }
        },
        setAccess() {
            const realData = this.decryptData(localStorage.getItem('sellexx_origin_verification'), this.appKey)
            this.accessObject = {
                type: realData ? realData.type : 'invalid',
                errors: this.errors,
                warns: this.warns,
            }
        },
        checkErrors() {
            const realData = this.decryptData(this.appKey, this.appSecret)
            const originalData = this.decryptData(this.appKey, localStorage.getItem('sellexx_origin_verification'))
            if(!originalData) {
                this.pushError('CSRF token mismatch. Reload the page and try again.')
            } else if(!originalData.key) {
                this.pushError('SELLEXX_DASHBOARD_KEY is invalid.')
            } else if (window.location.origin !== realData.host || window.location.origin !== originalData.host || realData.host !== originalData.host) {
                this.pushError(`Current host does not match the expected ${originalData.host}.`)
            } else if (realData.type !== originalData.type) {
                this.pushError('The type of the used license is incorrect.')
            } else if (currentTimestamp > realData.expired_at || currentTimestamp > originalData.expired_at) {
                this.pushError('Your license has expired.')
            } else {
                let left = originalData.expired_at - currentTimestamp
                let daysLeft = Math.round(left / 86400)
                if (daysLeft < 2) {
                    this.pushWarn('Your license will expire in less than 1 day.')
                } else if (daysLeft < 4) {
                    this.pushWarn('Your license will expire in less than 3 days.')
                } else if (daysLeft < 8) {
                    this.pushWarn('Your license will expire in less than 7 days.')
                }
            }


        },
        verifyKey(key, secret) {
            this.setAppKey(key, secret)

            this.clearErrors()
            this.clearWarns()

            if(this.decryptTimestamp() && (this.decryptTimestamp() + 86400) < currentTimestamp) {
                localStorage.removeItem('sellexx_verification')
                localStorage.removeItem('sellexx_origin_verification')
                localStorage.removeItem('sellexx_verification_timestamp')
            }

            if(!key) {
                this.pushError('SELLEXX_DASHBOARD_KEY was not found in the .env file.')
            } else if (!secret) {
                this.pushError('SELLEXX_DASHBOARD_SECRET was not found in the .env file.')
            } else {
                const realData = this.decryptData(this.appKey, this.appSecret)
                if (!realData) {
                    this.pushError('SELLEXX_DASHBOARD_KEY or SELLEXX_DASHBOARD_SECRET are invalid.')
                } else {
                    if(!localStorage.getItem('sellexx_verification') || !localStorage.getItem('sellexx_origin_verification') || localStorage.getItem('sellexx_verification') !== secret) {
                        this.$axios.post(
                            `https://exxtensio.com/api/licence/${key}`,
                            {},
                            {headers: {'X-Api-Key': 'YCwWdyr7JNfZM92mh4zQGxDsVFHk6n5aebpEvB8LA3RucKUtPT'}}
                        ).then((response) => {
                            localStorage.setItem('sellexx_verification', secret)
                            localStorage.setItem('sellexx_origin_verification', response.data)
                            localStorage.setItem('sellexx_verification_timestamp', this.encryptTimestamp(currentTimestamp))
                            this.checkErrors()
                        }).catch((error) => {
                            this.pushError(error.response.data.message)
                            this.checkErrors()
                        })
                    } else {
                        this.checkErrors()
                    }
                }
            }
            this.setAccess()
        }
    },
});

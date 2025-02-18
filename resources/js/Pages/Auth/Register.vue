<template>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center px-6 py-8 mx-auto md:h-screen lg:py-20">
            <exx-logo classes="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white" :auth="true"/>
            <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-900 dark:border-gray-700">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-base font-bold  text-center leading-tight tracking-tight text-gray-900 md:text-lg dark:text-white">
                        Create an account
                    </h1>
                    <fwb-alert v-if="disabled" class="text-center" type="warning" border>
                        To access the Admin Dashboard with full permissions, first create an Artisan user.
                    </fwb-alert>
                    <div class="space-y-4 md:space-y-6">
                        <fwb-input
                            v-model="name"
                            type="text"
                            label="Name"
                            placeholder="Enter your name"
                            size="md"
                            :disabled="disabled"
                            class="focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('name') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('name')" #validationMessage>
                                {{ errors.name[0] }}
                            </template>
                        </fwb-input>
                        <fwb-input
                            v-model="email"
                            type="email"
                            label="Email"
                            placeholder="Enter your email"
                            size="md"
                            :disabled="disabled"
                            class="focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('email') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('email')" #validationMessage>
                                {{ errors.email[0] }}
                            </template>
                        </fwb-input>
                        <fwb-input
                            v-model="password"
                            type="password"
                            label="Password"
                            placeholder="••••••••"
                            size="md"
                            :disabled="disabled"
                            class="focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('password') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('password')" #validationMessage>
                                {{ errors.password[0] }}
                            </template>
                        </fwb-input>
                        <fwb-input
                            v-model="password_confirmation"
                            type="password"
                            label="Confirm password"
                            placeholder="••••••••"
                            size="md"
                            :disabled="disabled"
                            class="focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('password') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('password')" #validationMessage>
                                {{ errors.password[0] }}
                            </template>
                        </fwb-input>

                        <fwb-button color="default" size="md" @click="register" class="w-full justify-center" :loading="loading" :disabled="loading">
                            Create an account
                        </fwb-button>
                        <p v-if="!disabled" class="text-sm text-gray-500 dark:text-gray-400">
                            Already have an account? <a class="btn-link hover:underline" href="/login">Sign in</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <exx-notification/>
    </div>
</template>

<script>
import axios from "axios";
import Base from "@/mixins/Base.js";
import {router} from "@inertiajs/vue3";

export default {
    mixins: [Base],
    props: {
        exists: {
            type: Boolean,
            default: false,
        },
        artisan: {
            type: Object,
            default: {
                email: null,
                name: null,
                password: null
            }
        }
    },
    created() {
        if (!this.exists) {
            this.email = this.artisan.email
            this.name = this.artisan.name
            this.password = this.artisan.password
            this.password_confirmation = this.artisan.password
        }
    },
    data() {
        return {
            loading: false,
            disabled: !this.exists,
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            errors: {}
        }
    },
    methods: {
        async register() {
            this.errors = {}
            this.loading = true
            await axios.post(this.getUrl('signup'), {
                install: !this.exists,
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }).then(response => {
                router.visit('/admin')
            }).catch(errors => {
                this.loading = false
                if (errors.response.status === 422) {
                    this.errors = errors.response.data.errors
                } else {
                    let message = this.$t('An unexpected error occurred. Please try again later.')
                    if (errors.response.status === 401 || errors.response.status === 429 || errors.response.status === 500) message = this.$t(errors.response.data.message)
                    else if (errors.response.status === 419) message = this.$t('CSRF token mismatch. Reload the page and try again.')

                    this.emitter.emit('notify', {
                        type: 'danger',
                        message: message
                    })
                }
            })
        }
    }
}
</script>

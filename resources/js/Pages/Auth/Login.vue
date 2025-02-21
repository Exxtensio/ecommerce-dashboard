<template>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center px-6 py-8 mx-auto md:h-screen lg:py-20">
            <exx-logo classes="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white" :auth="true"/>
            <div class="w-full md:mt-0 sm:max-w-md xl:p-0 theme-brand-bg rounded-md">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-base font-bold  text-center leading-tight tracking-tight text-gray-900 md:text-lg dark:text-white">
                        {{ $t('Sign in to your account') }}
                    </h1>
                    <div class="space-y-4 md:space-y-6">
                        <fwb-input
                            v-model="email"
                            type="email"
                            :label="$t('Email')"
                            :placeholder="$t('Enter your email')"
                            size="md"
                            class="required component edit-component text-field focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('email') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('email')" #validationMessage>
                                {{ errors.email[0] }}
                            </template>
                        </fwb-input>
                        <fwb-input
                            v-model="password"
                            type="password"
                            :label="$t('Password')"
                            placeholder="••••••••"
                            size="md"
                            class="required component edit-component text-field focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('password') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('password')" #validationMessage>
                                {{ errors.password[0] }}
                            </template>
                        </fwb-input>

                        <div class="flex items-center justify-between">
                            <fwb-checkbox v-model="remember" :label="$t('Remember me')"/>
<!--                            <a class="text-xs btn-link hover:underline" href="/forgot-password">{{ $t('Forgot password?') }}</a>-->
                        </div>
                        <fwb-button color="default" size="md" @click="login" class="btn-primary !block w-full">{{ $t('Sign in') }}</fwb-button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $t('Don\'t have an account yet?') }} <a class="btn-link hover:underline" href="/admin/register">{{ $t('Sign up') }}</a>
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
    data() {
        return {
            email: 'vladlen.beilik@gmail.com',
            password: '05091987Vb',
            remember: true,
            errors: {}
        }
    },
    methods: {
        async login() {
            this.errors = {}
            await axios.post(this.getUrl('admin/login'), {
                email: this.email,
                password: this.password,
                remember: this.remember,
            }).then(response => {
                router.visit('/admin')
            }).catch(errors => {
                if (errors.response.status === 422) {
                    this.errors = errors.response.data.errors
                } else {
                    let message = this.$t('An unexpected error occurred. Please try again later.')
                    if (errors.response.status === 401 || errors.response.status === 429) message = this.$t(errors.response.data.message)
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

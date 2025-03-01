<template>
    <div class="bg-gray-50 dark:bg-gray-900">
        <div class="flex flex-col items-center px-6 py-8 mx-auto md:h-screen lg:py-20">
            <exx-logo classes="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white" :auth="true"/>
            <div class="w-full md:mt-0 sm:max-w-md xl:p-0 theme-brand-bg rounded-md">
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1 class="text-base font-bold  text-center leading-tight tracking-tight text-gray-900 md:text-lg dark:text-white">
                        {{ $t('Create an account') }}
                    </h1>
                    <div class="space-y-4 md:space-y-6">
                        <fwb-input
                            v-model="name"
                            type="text"
                            :label="$t('Name')"
                            :placeholder="$t('Enter your name')"
                            size="md"
                            class="required component edit-component text-field focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('name') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('name')" #validationMessage>
                                {{ errors.name[0] }}
                            </template>
                        </fwb-input>
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
                        <fwb-input
                            v-model="password_confirmation"
                            type="password"
                            :label="$t('Confirm password')"
                            placeholder="••••••••"
                            size="md"
                            class="required component edit-component text-field focus:outline-none focus:ring-2"
                            :validation-status="errors.hasOwnProperty('password') ? 'error' : null"
                        >
                            <template v-if="errors.hasOwnProperty('password')" #validationMessage>
                                {{ errors.password[0] }}
                            </template>
                        </fwb-input>

                        <fwb-button color="default" size="md" @click="register" class="btn-primary w-full justify-center" :loading="loading" :disabled="loading">
                            {{ $t('Create an account') }}
                        </fwb-button>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ $t('Already have an account?') }} <a class="btn-link hover:underline" href="/admin/login">{{ $t('Sign in') }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <exx-notification/>
    </div>
</template>

<script>
import Base from "@/mixins/Base.js";

export default {
    mixins: [Base],
    data() {
        return {
            loading: false,
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
            await this.$axios.post(this.getUrl('admin/signup'), {
                name: this.name,
                email: this.email,
                password: this.password,
                password_confirmation: this.password_confirmation,
            }).then(response => {
                this.$router.visit('/admin')
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

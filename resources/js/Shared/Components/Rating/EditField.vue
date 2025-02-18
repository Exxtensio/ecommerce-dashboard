<template>
    <div :class="{'required': isRequired, 'readonly': field.readonly}">
        <div class="flex items-center space-x-4">
            <label class="block text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
            <div class="-mt-[5px]">
                <star-rating
                    v-if="theme === 'dark'"
                    v-model:rating="field.resolvedForUpdate"
                    :show-rating="false"
                    star-size="18"
                    active-color="#eab308"
                    inactive-color="#6b7280"
                />
                <star-rating
                    v-if="theme === 'light'"
                    v-model:rating="field.resolvedForUpdate"
                    :show-rating="false"
                    star-size="18"
                    active-color="#eab308"
                    inactive-color="#d1d5db"
                />
            </div>
        </div>
        <exx-help-text :text="field.helpText" type="text"/>
    </div>
</template>

<script>
export default {
    props: {
        field: {
            type: Object,
            required: true
        }
    },
    computed: {
        theme() {
            return localStorage.getItem('sellexx_theme_type')
        }
    }
}
</script>

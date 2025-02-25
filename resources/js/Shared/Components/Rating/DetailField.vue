<template>
    <div class="readonly" :class="{'required': isRequired}">
        <div class="flex items-center space-x-4">
            <label class="block text-sm font-medium text-gray-900 dark:text-white">{{ field.name }}</label>
            <div class="-mt-[5px]">
                <star-rating
                    v-model:rating="field.resolvedForDisplay"
                    :show-rating="false"
                    read-only
                    star-size="18"
                    active-color="#eab308"
                    inactive-color="transparent"
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
    }
}
</script>

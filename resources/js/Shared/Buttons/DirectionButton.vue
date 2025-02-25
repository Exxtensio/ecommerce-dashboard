<template>
    <div class="flex items-center ms-3">
        <button @click.prevent="toggle" type="button" class="w-9 h-9 text-xs btn-default">
            {{ direction === 'rtl' ? 'LTR' : 'RTL' }}
        </button>
    </div>
</template>

<script>
export default {
    data() {
        return {
            direction: localStorage.getItem('sellexx_direction')
        }
    },
    methods: {
        toggle() {
            const oldDirection = this.direction
            localStorage.setItem('sellexx_direction', this.direction = oldDirection === 'rtl' ? 'ltr' : 'rtl')
            document.getElementById('main').setAttribute('dir', this.direction)
        }
    }
}
</script>

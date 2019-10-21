<template>
    <label class="user-select-none ant-checkbox-wrapper" :class="{'ant-checkbox-wrapper-checked': checked}">
        <span class="ant-checkbox" :class="{'ant-checkbox-checked': checked}">
            <input type="checkbox" class="ant-checkbox-input" v-model="checked" />
            <span class="ant-checkbox-inner"></span>
        </span>
        <span>
            <slot />
        </span>
    </label>
</template>

<script>
export default {
    props: ['value'],
    data() {
        return {
            checked: false
        }
    },
    watch: {
        checked(val) {
            if (val !== this.value) {
                this.$emit('change', val)
                this.$emit('input', val)
            }
        },
        value(val) {
            this.checked = val
        }
    },
    mounted() {
        this.checked = this.value
    }
}
</script>
<template>
    <router-link
        :class="className"
        :to="href"
        :title="title"
        style="padding: 0 5px"
    >
        <a-icon :type="icon" :style="{ fontSize: '14px' }" />
    </router-link>
</template>

<script>
const sizes = {
    small: 'ant-btn-sm',
    large: 'ant-btn-lg',
}

export default {
    props: {
        href: {},
        title: {},
        type: {
            default: 'default'
        },
        size: {
            default: 'default'
        },
        icon: {},
    },
    computed: {
        className() {
            let data = {
                'ant-btn': true,
            }
            let size = sizes[this.size]
            if (size) {
                data[size] = true
            }
            if (this.type === 'primary') {
                data['ant-btn-primary'] = true
            }
            if (this.type === 'default') {
                data['ant-btn-default'] = true
            }
            return data
        }
    },
}
</script>

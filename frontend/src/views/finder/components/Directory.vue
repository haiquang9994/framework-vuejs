<template>
    <ul>
        <li v-for="(item, key) in directories" :key="key">
            <span @click="click(item)" :class="{active: data.active == item.path}">{{ item.label }}</span>
            <directory :directories="item.children" @click="clickChild" :data="data" />
        </li>
    </ul>
</template>

<script>
export default {
    name: 'Directory',
    props: ['directories', 'data'],
    methods: {
        click(item) {
            this.$emit('click', item)
        },
        clickChild(item) {
            this.click(item)
        }
    }
}
</script>

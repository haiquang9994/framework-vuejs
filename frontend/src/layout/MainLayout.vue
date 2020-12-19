<template>
    <a-layout class="main-layout">
        <Sidebar />
        <a-layout>
            <GlobalHeader />
            <a-layout-content class="router-view-wrap">
                <router-view></router-view>
            </a-layout-content>
            <a-layout-footer style="textalign: center"
                >Admin ©2018 Created by Phuong Nam Digital</a-layout-footer
            >
        </a-layout>
    </a-layout>
</template>

<script>
import Sidebar from './Sidebar'
import GlobalHeader from './GlobalHeader'

export default {
    name: 'MainLayout',
    components: {
        Sidebar,
        GlobalHeader,
    },
    data() {
        return {
            loadding: false,
            menu: null,
        }
    },
}
</script>

<style lang="scss" scoped>
.router-view-wrap {
    padding: 18px;
    background: #fff;
    min-height: 360px;
    margin: 15px;
}
</style>

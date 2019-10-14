<template>
    <section>
        <main-layout v-if="$store.state.layout.use_layout"></main-layout>
        <router-view v-else></router-view>
        <loading id="loader-wrapper" :active.sync="loaderVisible" loader="dots"></loading>
    </section>
</template>
<script>
import Loading from 'vue-loading-overlay'
import MainLayout from "@/layout/MainLayout"

export default {
    components: {
        Loading,
        MainLayout
    },
    data() {
        return {
            loaderVisible: true,
            use_layout: null
        }
    },
    mounted() {
        let app = this
        setTimeout(() => {
            app.loaderVisible = false
        }, 500)
    }
}
</script>

<style lang="scss">
#loader-wrapper {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 1000;
    text-align: center;
    background-color: #ffffff;
    .vld-icon,
    svg {
        min-height: 100vh;
    }
}
.user-select-none {
    user-select: none;
}
</style>

<style lang="scss">
@import "./scss/app.scss";
</style>

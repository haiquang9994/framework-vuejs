<template>
    <a-layout-sider breakpoint="lg" collapsedWidth="0" :collapsed="$store.getters.sidebarCollapsed">
        <div class="logo">
            <span
                style="color: #ffffff;font-weight: bold;font-size: 16px;line-height: 32px;text-align: center;display: block;"
            >Admin</span>
        </div>
        <a-menu theme="dark" :selectedKeys="$store.getters.sidebarSelected">
            <a-menu-item v-for="nav in navs" :key="nav.key">
                <router-link :to="nav.link">
                    <i class="awesome-icon" v-if="nav.icon.provider === 'awesome'">
                        <fai :icon="['fas', nav.icon.key]" />
                    </i>
                    <a-icon v-else :type="nav.icon.key" />
                    <span class="nav-text">{{ nav.text }}</span>
                </router-link>
            </a-menu-item>
        </a-menu>
    </a-layout-sider>
</template>

<script>
import navs from '../navs'

export default {
    data() {
        return {
            navs: navs
        }
    },
}
</script>
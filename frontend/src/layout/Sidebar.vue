<template>
    <a-layout-sider breakpoint="lg" collapsedWidth="0" v-model="sidebarOptions.collapsed">
      <div class="logo">
        <span style="color: #ffffff;font-weight: bold;font-size: 16px;line-height: 32px;text-align: center;display: block;">Admin</span>
      </div>
      <a-menu theme="dark" v-model="$store.state.activeMenu">
        <a-menu-item v-for="nav in navs" :key="nav.key">
          <router-link :to="nav.link">
            <a-icon :type="nav.icon" />
            <span class="nav-text">{{ nav.text }}</span>
          </router-link>
        </a-menu-item>
      </a-menu>
    </a-layout-sider>
</template>

<script>
import navs from '../navs'

export default {
    name: 'Sidebar',
    data() {
        return {
            navs: navs
        }
    },
    props: ['sidebarOptions'],
}
</script>
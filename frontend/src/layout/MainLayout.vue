<template>
  <a-layout id="app">
    <sidebar :sidebarOptions="sidebarOptions" />
    <a-layout>
      <global-header :sidebarOptions="sidebarOptions" />
      <a-layout-content :style="{ margin: '15px' }">
        <div :style="{ padding: '18px', background: '#fff', minHeight: '360px' }">
          <slot />
        </div>
      </a-layout-content>
      <a-layout-footer style="textAlign: center">Admin ©2018 Created by Phuong Nam Digital</a-layout-footer>
    </a-layout>
  </a-layout>
</template>

<script>
import Sidebar from './Sidebar'
import GlobalHeader from './GlobalHeader'
import Vue from 'vue'

export default {
  name: "MainLayout",
  components: {
    Sidebar,
    GlobalHeader,
  },
  data () {
    return {
      sidebarOptions: {
        collapsed: false,
      },
      loadding: false,
      Vue: Vue,
      menu: null,
    }
  },
};
</script>

<style lang="scss">
#app {
  min-height: 100vh;
  .logo {
    height: 32px;
    background: rgba(255,255,255,.2);
    margin: 16px;
  }
  .trigger {
    font-size: 18px;
    line-height: 64px;
    padding: 0 24px;
    cursor: pointer;
    transition: color .3s;
    &:hover {
      color: #1890ff;
    }
  }
}
.global-header-right {
  float: right;
}
.ant-layout-header {
  padding-right: 12px;
}
.header-item {
  height: 100%;
  display: inline-block;
  padding: 0 16px;
  cursor: pointer;
  &:hover {
    background-color: #cdcdcd;
  }
}
#header-header-notice {
  .header-notice-icon {
    font-size: 16px;
    padding: 4px;
  }
  .ant-badge-count {
    font-size: 10px;
    height: 16px;
    line-height: 16px;
    padding: 0 4px;
    text-align: center;
    top: -5px;
  }
}
#account-options {
  user-select: none;
}
</style>

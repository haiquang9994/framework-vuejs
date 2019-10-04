<template>
  <a-layout id="app">
    <sidebar :sidebarOptions="sidebarOptions" />
    <a-layout>
      <global-header :sidebarOptions="sidebarOptions" />
      <a-layout-content :style="{ margin: '15px' }">
        <div :style="{ padding: '24px', background: '#fff', minHeight: '360px' }">
          <router-view></router-view>
        </div>
      </a-layout-content>
      <a-layout-footer style="textAlign: center">
        Admin ©2018 Created by Phuong Nam Digital
      </a-layout-footer>
    </a-layout>
    <loading id="loader-wrapper" :active.sync="loaderVisible" loader="dots"></loading>
  </a-layout>
</template>
<script>
import Sidebar from './layouts/Sidebar'
import GlobalHeader from './layouts/GlobalHeader'
import Loading from 'vue-loading-overlay'
import Vue from 'vue'

export default {
  components: {
    Loading,
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
      loaderVisible: true
    }
  },
  mounted () {
    let app = this
    setTimeout(() => {
      app.loaderVisible = false
    }, 500)
    // this.$store.state.name = 'Nguyen'
    this.$store.commit('setName', {
      name: 'Nguyen Hai Quang'
    })
    console.log(this.$store.state.name)
  },
  methods: {
  }
}
</script>

<style>
#app {
  min-height: 100vh;
}
#app .logo {
  height: 32px;
  background: rgba(255,255,255,.2);
  margin: 16px;
}
#app .trigger {
  font-size: 18px;
  line-height: 64px;
  padding: 0 24px;
  cursor: pointer;
  transition: color .3s;
}

#app .trigger:hover {
  color: #1890ff;
}

#app .logo {
  height: 32px;
  background: rgba(255,255,255,.2);
  margin: 16px;
}
</style>
<style lang="scss">
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
#loader-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1000;
  text-align: center;
  background-color: #ffffff;
  .vld-icon, svg {
    min-height: 100vh;
  }
}
</style>

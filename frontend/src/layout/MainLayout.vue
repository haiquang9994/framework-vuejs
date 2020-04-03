<template>
    <a-layout class="main-layout">
        <sidebar :sidebarOptions="sidebarOptions" />
        <a-layout>
            <global-header :sidebarOptions="sidebarOptions" />
            <a-layout-content :style="{ margin: '15px' }">
                <ul class="route-tabs">
                    <li
                        class="route-tab route-tab-enable"
                        v-for="(tab, i) in $store.state.layout.tabs"
                        :key="i"
                        :class="{active: tab.fullPath === $store.state.layout.active_tab}"
                        @click="click_tab($event, tab)"
                        @click.middle="close_tab(tab)"
                    >
                        <span class="route-tab-label route-tab-enable">{{ tab.label }}</span>
                        <fai
                            v-if="tab.name !== 'dashboard'"
                            class="route-tab-icon"
                            :icon="['fas', 'times']"
                            size="xs"
                            @click="close_tab(tab)"
                        />
                    </li>
                </ul>
                <div :style="{ padding: '18px', background: '#fff', minHeight: '360px' }">
                    <keep-alive>
                        <router-view :key="$route.fullPath"></router-view>
                    </keep-alive>
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
    name: 'MainLayout',
    components: {
        Sidebar,
        GlobalHeader,
    },
    data() {
        return {
            sidebarOptions: {
                collapsed: false,
            },
            loadding: false,
            Vue: Vue,
            menu: null,
        }
    },
    methods: {
        click_tab(e, tab) {
            if (e.target.classList.contains('route-tab-enable') && this.$route.fullPath !== tab.fullPath) {
                this.$go(tab.fullPath)
            }
        },
        do_close_tab(tab) {
            let i = this.$store.state.layout.tabs.indexOf(this.$store.state.layout.tabs.find(t => t.fullPath === tab.fullPath))
            this.$store.state.layout.tabs.splice(i, 1)
            let close_i = this.$store.state.layout.tab_history.indexOf(tab.fullPath)
            this.$store.state.layout.tab_history.splice(close_i, 1)
            if (this.$store.state.layout.active_tab === tab.fullPath) {
                let last_tab_full_path = this.$store.state.layout.tab_history[this.$store.state.layout.tab_history.length - 1],
                    last_tab = this.$store.state.layout.tabs.find(t => t.fullPath === last_tab_full_path)
                if (last_tab) {
                    this.$go(last_tab.fullPath)
                }
            }
        },
        close_tab(tab) {
            if (tab.name === 'dashboard') {
                return
            }
            this.do_close_tab(tab)
            this.$store.commit('save')
        }
    }
}
</script>

<style lang="scss" scoped>
.route-tabs {
    padding: 0;
    margin: 0;
    list-style: none;
    display: flex;
    li {
        padding: 3px 10px 3px;
        margin: 0 3px 0 0;
        user-select: none;
        cursor: pointer;
        border-radius: 4px 4px 0 0;
        background-color: #e2e2e2;
        &.active {
            background-color: #fff;
        }
        .route-tab-label {
            margin-right: 5px;
        }
        .route-tab-icon {
            padding: 2px;
            width: 13px;
            height: 13px;
            transition: all 200ms;
            border-radius: 3px;
            &:hover {
                background-color: #636262;
                color: #fff;
            }
        }
    }
}
</style>

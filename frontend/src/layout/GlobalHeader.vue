<template>
    <a-layout-header style="background: #fff; padding: 0">
        <a-icon
            v-responsive.lg.xl
            class="trigger"
            :type="
                $store.getters.sidebarCollapsed ? 'menu-unfold' : 'menu-fold'
            "
            @click="() => $store.commit('layout.sidebar.toggle')"
        />
        <div class="global-header-right">
            <a-dropdown
                id="header-header-notice"
                class="header-item"
                :trigger="['click']"
            >
                <span class="header-notice">
                    <a-badge count="3">
                        <a-icon :class="['header-notice-icon']" type="bell" />
                    </a-badge>
                </span>
                <template slot="overlay">
                    <a-list class="header-notice-list">
                        <a-list-item class="header-notice-item"
                            >Notice 1</a-list-item
                        >
                        <a-list-item class="header-notice-item"
                            >Notice 2</a-list-item
                        >
                        <a-list-item class="header-notice-item"
                            >Notice 3</a-list-item
                        >
                    </a-list>
                </template>
            </a-dropdown>
            <a-dropdown
                class="header-item"
                id="account-options"
                :trigger="['hover']"
            >
                <span>
                    <a-avatar :src="$assets('user.png')" />
                    <span>{{ $store.getters.user.fullname }}</span>
                </span>
                <a-menu slot="overlay">
                    <a-menu-item @click="$go('/profile')">
                        <a-icon type="user" />Profile
                    </a-menu-item>
                    <a-menu-item @click="logout">
                        <a-icon type="logout" />Sign out
                    </a-menu-item>
                </a-menu>
            </a-dropdown>
        </div>
    </a-layout-header>
</template>

<script>
export default {
    methods: {
        fetchNotice() {
            if (this.loadding) {
                this.loadding = false
                return
            }
            this.loadding = true
            setTimeout(() => {
                this.loadding = false
            }, 2000)
        },
        logout() {
            this.$http.delete(process.env.VUE_APP_API_ENDPOINT + 'logout')
                .authed(this.$token())
                .sent()
                .then(() => {
                    this.clean()
                })
                .catch(() => {
                    this.clean()
                })
        },
        clean() {
            this.$cookies.remove('token', null)
            this.$store.commit('user.unload')
            this.$go('/login')
        }
    },
}
</script>

<style lang="scss" scoped>
.header-notice-list {
    min-width: 200px;
}
.header-notice-item {
    padding: 5px 12px;
    cursor: pointer;
    &:hover {
        background-color: #e6f7ff;
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
.header-item {
    height: 100%;
    display: inline-block;
    padding: 0 16px;
    cursor: pointer;
    &:hover {
        background-color: #cdcdcd;
    }
}
</style>

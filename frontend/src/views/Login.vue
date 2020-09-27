<template>
    <div class="container">
        <div class="content">
            <div class="top">
                <div class="header">
                    <img alt="logo" class="logo" :src="$assets('logo.png')" />
                    <span class="title">Admin Page</span>
                </div>
                <div class="desc">Login for begin work session.</div>
            </div>
            <div class="login">
                <a-form-item>
                    <a-input
                        size="large"
                        placeholder="Email"
                        v-model="email"
                        @keydown.enter="handleSubmit"
                    >
                        <a-icon slot="prefix" type="user" />
                    </a-input>
                </a-form-item>
                <a-form-item>
                    <a-input
                        size="large"
                        placeholder="Password"
                        v-model="password"
                        type="password"
                        @keydown.enter="handleSubmit"
                    >
                        <a-icon slot="prefix" type="lock" />
                    </a-input>
                </a-form-item>
                <a-form-item>
                    <a-checkbox v-model="rememberMe">Remember me</a-checkbox>
                </a-form-item>
                <a-form-item>
                    <a-button
                        style="width: 100%; margin-top: 24px"
                        size="large"
                        htmlType="submit"
                        type="primary"
                        @click="handleSubmit"
                        :loading="processing"
                        >Login</a-button
                    >
                </a-form-item>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            processing: false,
            rememberMe: false,
            email: 'admin@gmail.com',
            password: '1234',
        }
    },
    beforeCreate() {
        this.form = this.$form.createForm(this)
    },
    mounted() {
        this.$store.commit('layout.global.loader.hide')
    },
    methods: {
        handleSubmit() {
            this.processing = true
            this.$http
                .post(process.env.VUE_APP_API_ENDPOINT + 'login')
                .withBody({
                    email: this.email,
                    password: this.password
                })
                .sent()
                .then(body => {
                    if (body.status && body.token) {
                        setTimeout(() => {
                            this.processing = false
                            if (this.rememberMe) {
                                this.$cookies.set('token', body.token, 60 * 60 * 24 * 30)
                            } else {
                                this.$cookies.set('token', body.token, 0)
                            }
                            this.$store.commit('user.apply', body.user_data)
                            this.$store.commit('user.load')
                            this.$go('/', true)
                        }, 500)
                    } else {
                        setTimeout(() => {
                            this.processing = false
                            this.$message.info(body.message)
                        }, 500)
                    }
                })
                .catch(() => {
                    this.processing = false
                })
        }
    },
}
</script>

<style lang="scss" scoped>
.container {
    .content {
        padding-top: 120px;
        .top {
            text-align: center;
            .header {
                height: 44px;
                line-height: 44px;
                .logo {
                    height: 44px;
                    vertical-align: top;
                    margin-right: 16px;
                }
                .title {
                    font-size: 28px;
                    color: rgba(0, 0, 0, 0.85);
                    font-family: -apple-system, BlinkMacSystemFont, Segoe UI,
                        Helvetica, Arial, sans-serif, Apple Color Emoji,
                        Segoe UI Emoji;
                    font-weight: 700;
                    position: relative;
                    top: 2px;
                }
            }
            .desc {
                font-size: 14px;
                color: rgba(0, 0, 0, 0.45);
                margin-top: 12px;
                margin-bottom: 40px;
            }
        }
        .login {
            width: 368px;
            margin: 0 auto;
        }
    }
}
</style>

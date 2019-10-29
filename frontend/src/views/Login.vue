<template>
    <div class="container">
        <div class="content">
            <div class="top">
                <div class="header">
                    <img
                        alt="logo"
                        class="logo"
                        src="https://iczer.gitee.io/vue-antd-pro/static/img/vue-antd-logo.png"
                    />
                    <span class="title">Admin Page</span>
                </div>
                <div class="desc">Login for begin work session.</div>
            </div>
            <div class="login">
                <a-form :form="form" @submit="handleSubmit">
                    <a-form-item>
                        <a-input size="large" placeholder="admin@gmail.com" v-model="email">
                            <a-icon slot="prefix" type="user" />
                        </a-input>
                    </a-form-item>
                    <a-form-item>
                        <a-input size="large" placeholder="1234" v-model="password">
                            <a-icon slot="prefix" type="lock" />
                        </a-input>
                    </a-form-item>
                    <my-checkbox v-model="rememberMe">Remember me</my-checkbox>
                    <a-form-item>
                        <a-button
                            style="width: 100%;margin-top: 24px"
                            size="large"
                            htmlType="submit"
                            type="primary"
                            :loading="logging"
                        >Login</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            logging: false,
            rememberMe: false,
            email: "admin@gmail.com",
            password: "1234"
        }
    },
    beforeCreate() {
        this.form = this.$form.createForm(this)
        document.title = 'Admin Page | Login'
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault()
            this.logging = true
            this.$http
                .post(process.env.VUE_APP_API_ENDPOINT + "login")
                .withBody({
                    email: this.email,
                    password: this.password
                })
                .sent()
                .then(body => {
                    if (body.status && body.token) {
                        this.logging = false
                        if (this.rememberMe) {
                            this.$cookies.set('token', body.token, 60 * 60 * 24 * 30)
                        } else {
                            this.$cookies.set('token', body.token, 0)
                        }
                        this.$c({ token: body.token, user_data: body.user_data, me_loaded: true }, true)
                        this.$go('/')
                    }
                })
                .catch(() => {
                    this.logging = false
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

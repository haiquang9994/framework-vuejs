<template>
    <div>
        <page-title title="Profile"></page-title>
        <a-form :form="form">
            <a-form-item label="Fullname" :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-input v-model="profile.fullname"></a-input>
            </a-form-item>
            <a-form-item label="Email" :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
                <a-input v-model="profile.email" :readOnly="true"></a-input>
            </a-form-item>
            <a-form-item :label-col="{ span: 5 }">
                <div class="ant-col-12 ant-col-offset-5">
                    <a-button :loading="logging" type="primary" @click="submit">Save</a-button>
                </div>
            </a-form-item>
        </a-form>
    </div>
</template>

<script>
import PageTitle from '@/components/PageTitle'

export default {
    name: 'Profile',
    components: {
        PageTitle
    },
    data() {
        return {
            form: this.$form.createForm(this),
            logging: false,
            profile: {
                fullname: this.$store.state.user_data.fullname,
                email: this.$store.state.user_data.email,
            }
        }
    },
    methods: {
        submit() {
            this.$http.put(process.env.API_ENDPOINT + 'me')
                .withBody({
                    fullname: this.profile.fullname
                })
                .authed(this.$store.state.token)
                .sent()
                .then(body => {
                    if (body.status) {
                        this.$store.state.user_data = body.user_data
                    }
                })
        }
    }
}
</script>
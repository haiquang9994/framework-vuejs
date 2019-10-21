<template>
    <div>
        <page-title title="Profile"></page-title>
        <a-form-item label="Email" :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
            <a-input v-model="profile.email" :readOnly="true"></a-input>
        </a-form-item>
        <a-form-item label="Fullname" :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
            <a-input v-model="profile.fullname"></a-input>
        </a-form-item>
        <a-form-item :wrapper-col="{ lg: 18, offset: 5 }">
            <a-button :loading="processing" type="primary" @click="save">Save</a-button>
        </a-form-item>
    </div>
</template>

<script>
export default {
    data() {
        return {
            processing: false,
            profile: {
                fullname: this.$store.state.user_data.fullname,
                email: this.$store.state.user_data.email,
            }
        }
    },
    methods: {
        save() {
            this.processing = true
            this.$http.put(process.env.VUE_APP_API_ENDPOINT + 'me')
                .withBody({
                    fullname: this.profile.fullname
                })
                .authed(this.$token())
                .sent()
                .then(body => {
                    if (body.status) {
                        this.processing = false
                        this.$store.state.user_data = body.user_data
                        this.$message.info(body.message)
                    }
                })
        }
    }
}
</script>
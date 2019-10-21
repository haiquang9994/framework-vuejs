<template>
    <div>
        <page-title title="Setting"></page-title>
        <a-form-item label="Website Name" :label-col="{ span: 5 }" :wrapper-col="{ span: 12 }">
            <a-input v-model="settings.website_name" />
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
            settings: {
                website_name: ''
            }
        }
    },
    methods: {
        save() {
            this.processing = true
            this.$http.put(process.env.VUE_APP_API_ENDPOINT + 'settings')
                .withBody(this.settings)
                .authed(this.$token())
                .sent()
                .then(response => {
                    if (response.status) {
                        this.$message.info(response.message)
                        this.processing = false
                    }
                })
        },
        pull() {
            this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'settings')
                .withBody({ _paging: 0 })
                .authed(this.$token())
                .sent()
                .then(response => {
                    if (response.status) {
                        let data = response.data
                        for (let i = 0; i < data.length; i++) {
                            let setting = data[i]
                            this.settings[setting.key] = setting.value
                        }
                    }
                })
        }
    },
    mounted() {
        this.pull()
    },
}
</script>
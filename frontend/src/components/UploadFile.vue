<template>
    <a-upload
        name="file"
        class="avatar-uploader"
        :showUploadList="false"
        :action="uploadEndPoint"
        :beforeUpload="beforeUpload"
        @change="handleChange"
    >
        <a-button>
            <a-icon type="upload" />Upload
        </a-button>
    </a-upload>
</template>

<script>
export default {
    data() {
        return {
            uploadEndPoint: this.$mergeUrl(process.env.VUE_APP_API_ENDPOINT, 'finder/upload?_token=' + this.$store.state.token)
        }
    },
    props: {
        beforeUpload: {
            type: Function,
            default: () => true,
        }
    },
    methods: {
        handleChange(info) {
            if (info.file.status === 'done') {
                let url = this.$mergeUrl(process.env.VUE_APP_WEB_URL, info.file.response.url)
                this.$emit('change', url)
            }
        }
    }
}
</script>

<template>
    <vodal
        :show="$store.state.file_manager.show"
        animation="zoom"
        @hide="$store.state.file_manager.show = false"
        :width="filemanager.width"
        :height="filemanager.height"
        :customStyles="{padding: '0'}"
    >
        <h2>File Manager</h2>
        <iframe ref="iframe" frameborder="0"></iframe>
    </vodal>
</template>

<script>
export default {
    data() {
        return {
            filemanager: {
                width: window.innerWidth - 200,
                height: window.innerHeight - 200,
            },
            iframe: {
                url: process.env.VUE_APP_WEB_URL + 'elfinder/index.html?_token=' + this.$store.state.token
            }
        }
    },
    created() {
        window.addEventListener('message', (e) => {
            let url = process.env.VUE_APP_WEB_URL + e.data.url
            if (e.data.command === 'getfile_tinymce') {
                window.tinymce_file_browser_callback_set_url(url)
            }
        })
    },
    watch: {
        '$store.state.file_manager.show': function (show) {
            if (show) {
                this.$refs.iframe.src = this.iframe.url + '&target=' + this.$store.state.file_manager.target
            } else {
                this.$refs.iframe.src = ''
            }
        }
    }
}
</script>

<style lang="scss" scoped>
    h2 {
        margin: 0;
        line-height: 40px;
        padding-left: 10px;
        user-select: none;
    }
    iframe {
        position: absolute;
        height: calc(100% - 40px);
        width: 100%;
        top: 40px;
    }
</style>
<style lang="scss">
    .vodal,
    .vodal-mask,
    .vodal-dialog {
        z-index: 65540;
    }
</style>
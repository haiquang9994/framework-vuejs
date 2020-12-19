<template>
    <vodal
        :show="$store.state.fileManager.enable"
        animation="zoom"
        @hide="$store.state.fileManager.enable = false"
        :width="filemanager.width"
        :height="filemanager.height"
        :customStyles="{padding: '0'}"
        :closeOnClickMask="false"
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
                width: Math.min(window.innerWidth - 200, 1320),
                height: Math.min(window.innerHeight - 200, 800),
            }
        }
    },
    created() {
        window.addEventListener('message', e => {
            if (e.data && e.data.message === 'file_manager') {
                let url = this.$mergeUrl(process.env.VUE_APP_WEB_URL, e.data.url)
                if (e.data.command === 'getfile_tinymce') {
                    window.tinymce_file_browser_callback_set_url(url)
                } else if (e.data.command === 'button_select_file') {
                    window.button_select_file_callback_set_url(url)
                }
                this.$filemanagerClose()
            }
        })
    },
    watch: {
        '$store.state.fileManager.enable': function (show) {
            if (show) {
                this.$refs.iframe.src = this.iframe_url + '&target=' + this.$store.state.fileManager.target
                document.scrollingElement.classList.add('file-manager-opened')
            } else {
                this.$refs.iframe.src = ''
                document.scrollingElement.classList.remove('file-manager-opened')
            }
        }
    },
    computed: {
        iframe_url() {
            return process.env.VUE_APP_WEB_URL + 'elfinder/index.html?_token=' + this.$cookies.get('token')
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
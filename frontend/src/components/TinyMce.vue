<template>
    <textarea ref="editor" :id="id"></textarea>
</template>

<script>
export default {
    data() {
        return {
            id: this.makeid(16),
        }
    },
    props: ['value'],
    activated() {
        this.$refs.editor.style = ''
        this.initMce()
    },
    watch: {
        value(value) {
            if (value !== tinyMCE.get(this.id).getContent()) {
                tinyMCE.get(this.id).setContent(value)
            }
        }
    },
    methods: {
        makeid(length) {
            let id = '',
                characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
                charactersLength = characters.length
            for (let i = 0; i < length; i++) {
                id += characters.charAt(Math.floor(Math.random() * charactersLength))
            }
            return id
        },
        removeOldMce() {
            let mce_list = document.querySelectorAll('.mce-tinymce')
            mce_list.forEach(mce => {
                mce.parentNode.removeChild(mce)
            })
        },
        initMce() {
            let vm = this, token = vm.$store.state.token, id = vm.id
            this.removeOldMce()
            this.$nextTick(function () {
                tinyMCE.init({
                    selector: '#' + id,
                    height: '600px',
                    plugins: 'code lists image media link table hr charmap directionality fullscreen',
                    toolbar: 'undo redo | bold italic underline strikethrough superscript subscript | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | image media link table hr charmap | ltr rtl | fullscreen | code',
                    menubar: false,
                    statusbar: false,
                    content_css: process.env.VUE_APP_WEB_URL + 'assets/content.css',
                    file_browser_callback(field_name, url, type, win) {
                        tinyMCE.activeEditor.windowManager.open({
                            file: process.env.VUE_APP_WEB_URL + 'elfinder/index.html?_token=' + token,
                            title: 'File Manager',
                            width: window.innerWidth - 200,
                            height: window.innerHeight - 200,
                        }, {
                            input: field_name,
                            setUrl(url) {
                                document.getElementById(field_name).value = url
                            }
                        })
                        return false
                    },
                    init_instance_callback(editor) {
                        tinyMCE.get(id).setContent(vm.value)
                        editor.on('input', (e) => {
                            vm.$emit('input', e.target.innerHTML)
                        })
                        editor.on('NodeChange', (e) => {
                            vm.$emit('input', editor.getContent())
                        })
                    }
                })
            })
        }
    },
}
</script>

<style lang="scss" scoped>
textarea {
    display: none;
}
</style>

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
            if (value !== window.tinyMCE.get(this.id).getContent()) {
                window.tinyMCE.get(this.id).setContent(value)
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
            let vm = this, id = vm.id
            this.removeOldMce()
            this.$nextTick(function () {
                window.tinyMCE.init({
                    selector: '#' + id,
                    entity_encoding: 'raw',
                    height: '600px',
                    plugins: 'code lists image media link table hr charmap directionality fullscreen',
                    toolbar1: 'undo redo | bold italic underline strikethrough superscript subscript | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | image media link table hr charmap | ltr rtl | fullscreen | code',
                    toolbar2: 'fontselect fontsizeselect formatselect',
                    menubar: false,
                    statusbar: false,
                    content_css: process.env.VUE_APP_WEB_URL + 'assets/content.css',
                    file_browser_callback(field_name) {
                        window.tinymce_file_browser_callback_set_url = url => {
                            document.getElementById(field_name).value = url
                        }
                        vm.$filemanagerOpen('getfile_tinymce')
                        return false
                    },
                    init_instance_callback(editor) {
                        window.tinyMCE.get(id).setContent(vm.value)
                        editor.on('input', () => {
                            vm.$emit('input', editor.getContent())
                        })
                        editor.on('NodeChange', () => {
                            vm.$emit('input', editor.getContent())
                        })
                        editor.on('focus', () => {
                            vm.$emit('focus')
                        })
                        editor.on('blur', () => {
                            vm.$emit('blur')
                        })
                    },
                    font_formats : "Andale Mono=andale mono,times;"+
                        "Arial=arial,helvetica,sans-serif;"+
                        "Arial Black=arial black,avant garde;"+
                        "Book Antiqua=book antiqua,palatino;"+
                        "Comic Sans MS=comic sans ms,sans-serif;"+
                        "Courier New=courier new,courier;"+
                        "Helvetica=helvetica;"+
                        "Tahoma=tahoma,arial,helvetica,sans-serif;"
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

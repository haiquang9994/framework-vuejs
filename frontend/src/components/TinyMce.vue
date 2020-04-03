<template>
    <textarea ref="editor" :id="id"></textarea>
</template>

<script>
import tinymce from 'tinymce'
import 'tinymce/themes/modern'
import 'tinymce/plugins/code'
import 'tinymce/plugins/lists'
import 'tinymce/plugins/image'
import 'tinymce/plugins/media'
import 'tinymce/plugins/link'
import 'tinymce/plugins/table'
import 'tinymce/plugins/hr'
import 'tinymce/plugins/charmap'
import 'tinymce/plugins/directionality'
import 'tinymce/plugins/fullscreen'

export default {
    data() {
        return {
            id: this.makeid(16),
            font_formats: "Andale Mono=andale mono,times;" +
                "Arial=arial,helvetica,sans-serif;" +
                "Arial Black=arial black,avant garde;" +
                "Book Antiqua=book antiqua,palatino;" +
                "Comic Sans MS=comic sans ms,sans-serif;" +
                "Courier New=courier new,courier;" +
                "Helvetica=helvetica;" +
                "Tahoma=tahoma,arial,helvetica,sans-serif;",
            cache: '',
            editor: null
        }
    },
    props: ['value'],
    watch: {
        value(content) {
            this.cache = content
            if (this.editor && this.editor.getContent() !== content) {
                this.editor.setContent(content)
            }
        }
    },
    deactivated() {
        if (this.editor) {
            this.cache = this.editor.getContent()
            this.editor.destroy()
            this.editor = null
        }
    },
    activated() {
        this.init()
    },
    methods: {
        destroy() {
            this.editor.destroy()
        },
        init() {
            this.$refs.editor.style = ''
            tinymce.init({
                selector: '#' + this.id,
                theme: 'modern',
                init_instance_callback: editor => {
                    this.editor = editor
                    editor.setContent(this.cache)
                    editor.on('input', () => {
                        this.$emit('input', editor.getContent())
                    })
                    editor.on('NodeChange', () => {
                        this.$emit('input', editor.getContent())
                    })
                    editor.on('focus', () => {
                        this.$emit('focus')
                    })
                    editor.on('blur', () => {
                        this.$emit('blur')
                    })
                },
                file_browser_callback(field_name) {
                    window.tinymce_file_browser_callback_set_url = url => {
                        document.getElementById(field_name).value = url
                    }
                    this.$filemanagerOpen('getfile_tinymce')
                    return false
                },
                entity_encoding: 'raw',
                height: '400px',
                plugins: 'code lists image media link table hr charmap directionality fullscreen',
                toolbar1: 'undo redo | bold italic underline strikethrough superscript subscript | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist',
                toolbar2: 'forecolor backcolor removeformat | image media link table hr charmap | ltr rtl | fullscreen | code',
                toolbar3: 'fontselect fontsizeselect formatselect',
                menubar: false,
                statusbar: false,
                content_css: process.env.VUE_APP_WEB_URL + 'assets/content.css',
                font_formats: this.font_formats,
                fontsize_formats: '7px 9px 11px 13px 15px 17px 19px 25px 30px 35px',
                formats: {
                    h1: { block: 'h1' },
                    h2: { block: 'h2' },
                    h3: { block: 'h3' },
                    h4: { block: 'h4' },
                    h5: { block: 'h5' },
                    h6: { block: 'h6' },
                }
            })
        },
        makeid(length) {
            let id = '',
                characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
                charactersLength = characters.length
            for (let i = 0; i < length; i++) {
                id += characters.charAt(Math.floor(Math.random() * charactersLength))
            }
            return id
        },
    },
}
</script>

<style scoped>
textarea {
    display: none;
}
</style>
<template>
    <div>
        <textarea ref="editor"></textarea>
    </div>
</template>

<script>
export default {
    data() {
        return {
            content: ''
        }
    },
    methods: {
        makeid(length) {
            let result = '',
                characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
                charactersLength = characters.length
            for (let i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength))
            }
            return result
        }
    },
    watch: {
        content(value) {
            this.$emit('input', value)
        }
    },
    props: ['value'],
    mounted() {
        let vm = this
        // this.content = this.value
        let id = this.makeid(16)
        this.$refs.editor.id = id
        let token = this.$store.state.token
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
                    width: win.innerWidth - 200,
                    height: win.innerHeight - 200,
                }, {
                    window: win,
                    input: field_name,
                    setUrl(url) {
                        document.getElementById(field_name).value = url
                    }
                });
                return false;
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
        });
    }
}
</script>

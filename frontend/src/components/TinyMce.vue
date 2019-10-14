<template>
    <tinymce-editor :id="key" :toolbar1="toolbar1" :other_options="other_options"></tinymce-editor>
</template>

<script>
export default {
    name: "TinyMce",
    data() {
        return {
            key: "nsg5a998nolh72tlafkx9ah702obzpq1leytofu5dbnqie98",
            toolbar1: 'undo redo | bold italic underline strikethrough superscript subscript | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent | numlist bullist | forecolor backcolor removeformat | image media link table hr charmap | ltr rtl | fullscreen | code',
            other_options: {
                menubar: false,
                statusbar: false,
                file_browser_callback(field_name, url, type, win) {
                    let activeUrl = ''
                    let closest = (element, class_name) => {
                        let node = element
                        while (node.tagName.toLowerCase() !== 'body') {
                            if (node.classList.contains(class_name)) {
                                return node
                            }
                            node = node.parentNode
                        }
                        return null
                    }
                    tinymce.activeEditor.windowManager.open(
                        {
                            file: '/admin/finder/popup',
                            title: 'File Manager',
                            width: 900,
                            height: 550,
                            resizable: 'yes',
                            buttons: [
                                {
                                    text: 'Insert',
                                    onclick() {
                                        if (activeUrl) {
                                            win.document.getElementById(field_name).value = activeUrl
                                        }
                                        tinymce.activeEditor.windowManager.close()
                                    }
                                },
                                {
                                    text: 'Close',
                                    onclick: 'close'
                                }
                            ]
                        },
                        {
                            setUrl(url, width, height) {
                                let field = win.document.getElementById(field_name)
                                field.value = url
                                let box = closest(field, 'mce-form')
                                let dimension_box = box.querySelectorAll('.mce-container.mce-abs-layout-item.mce-container')[2]
                                let inputs = dimension_box.querySelectorAll('.mce-container.mce-abs-layout-item input')
                                if (width > 600) {
                                    height = parseInt(height * 600 / width)
                                    width = 600
                                }
                                inputs[0].value = width
                                inputs[1].value = height
                            },
                            setActiveUrl(url) {
                                activeUrl = url
                            }
                        }
                    )
                },
                height: '300px'
            }
        }
    }
}
</script>
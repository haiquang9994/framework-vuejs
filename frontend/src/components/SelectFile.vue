<template>
    <a-button @click="showFileManager"> <a-icon type="folder" /> File manager </a-button>
</template>

<script>
export default {
    methods: {
        showFileManager() {
            window.button_select_file_callback_set_url = url => {
                this.$emit('change', url)
            }
            this.$filemanagerOpen('button_select_file')
        }
    }
}
</script>

<template>
    <editor :apiKey="key" :init="{ plugins: ['paste', 'link'] }"></editor>
</template>

<script>

export default {
    name: 'TinyMce',
    data() {
        return {
            key: 'nsg5a998nolh72tlafkx9ah702obzpq1leytofu5dbnqie98',
        }
    }
}
</script>
<template>
    <div class="page-title">
        <h4>{{ title }}</h4>
        <div class="page-title-actions">
            <slot />
        </div>
    </div>
</template>

<script>
export default {
    props: ['title']
}
</script>

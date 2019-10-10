<template>
  <div class="fm-file">
    <span class="fm-file-img" :style="{backgroundImage: 'url(' + item.image + ')'}"></span>
    <span class="fm-file-name">{{ item.filename }}</span>
  </div>
</template>

<script>
export default {
  name: "Item",
  props: ["item"]
};
</script>

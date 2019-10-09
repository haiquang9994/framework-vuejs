<template>
  <div>
    <page-title title="Posts">
      <router-link class="ant-btn ant-btn-primary" to="/posts/new">New</router-link>
    </page-title>
    <input type="text">
  </div>
</template>

<script>
import PageTitle from "@/components/PageTitle"

export default {
  name: 'ListPost',
  components: {
    PageTitle
  },
  data() {
    return {
    }
  }
};
</script>
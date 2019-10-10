<template>
  <div>
    <page-title title="New Post">
      <button class="ant-btn ant-btn-primary" @click="save">Save</button>
    </page-title>
    <div class="ant-row ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Title</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-input class="ant-input" placeholder="Enter title of post"/>
      </a-col>
    </div>
    <div class="ant-row ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Summary</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-textarea class="ant-input" placeholder="Enter summary of post" :autosize="{ minRows: 3, maxRows: 6 }" />
      </a-col>
    </div>
    <div class="ant-row ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Content  </label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <tiny-mce></tiny-mce>
      </a-col>
    </div>
  </div>
</template>

<script>
import PageTitle from "@/components/PageTitle"
import TinyMce from '@/components/TinyMce'

export default {
  name: 'NewPost',
  components: {
    PageTitle,
    'tiny-mce': TinyMce
  },
  data() {
    return {
    }
  },
  methods: {
    save() {
    }
  }
}
</script>
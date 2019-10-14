<template>
  <div>
    <page-title title="New Post">
      <a-button type="primary" @click="save">Save</a-button>
    </page-title>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Title</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-input class="ant-input" v-model="item.title" placeholder="Enter title of post"/>
      </a-col>
    </a-row>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Summary</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-textarea class="ant-input" v-model="item.summary" placeholder="Enter summary of post" :autosize="{ minRows: 3, maxRows: 6 }" />
      </a-col>
    </a-row>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Content</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <tiny-mce></tiny-mce>
      </a-col>
    </a-row>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Publish at</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-date-picker format="DD/MM/YYYY" v-model="item.published_at.date" />
        <span class="small-space"></span>
        <a-time-picker format="HH:mm" v-model="item.published_at.time" />
      </a-col>
    </a-row>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Active</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-radio-group name="radioGroup" :defaultValue="1">
          <a-radio :value="1">Yes</a-radio>
          <a-radio :value="0">No</a-radio>
        </a-radio-group>
      </a-col>
    </a-row>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3 }" class="ant-form-item-label">
        <label>Featured</label>
      </a-col>
      <a-col :lg="{ span: 18 }" class="ant-form-item-control">
        <a-radio-group name="radioGroup" :defaultValue="0">
          <a-radio :value="1">Yes</a-radio>
          <a-radio :value="0">No</a-radio>
        </a-radio-group>
      </a-col>
    </a-row>
    <a-row class="ant-form-item">
      <a-col :lg="{ span: 3, offset: 3 }">
        <a-button type="primary" @click="save">Save</a-button>
      </a-col>
    </a-row>
  </div>
</template>

<script>
import PageTitle from "@/components/PageTitle"
import TinyMce from '@/components/TinyMce'
import moment from 'moment'

export default {
  name: 'NewPost',
  components: {
    PageTitle,
    'tiny-mce': TinyMce
  },
  data() {
    return {
      item: {
        title: null,
        summary: null,
        content: null,
        published_at: {
          date: null,
          time: null
        },
        active: true,
        featured: false,
      }
    }
  },
  methods: {
    moment,
    save() {
      let data = {
        title: this.item.title,
        summary: this.item.summary,
        content: this.item.content,
        // published_at: 
      }
      console.log(this.item.published_at)
    }
  }
}
</script>
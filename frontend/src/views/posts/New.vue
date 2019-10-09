<template>
  <div>
    <page-title title="New Post">
      <button class="ant-btn ant-btn-primary" @click="save">Save</button>
    </page-title>
    <div class="ant-row ant-form-item">
      <div class="ant-col-5 ant-form-item-label">
        <label>Title</label>
      </div>
      <div class="ant-col-15 ant-form-item-control">
        <a-input class="ant-input" placeholder="Enter title of post"/>
      </div>
    </div>
    <div class="ant-row ant-form-item">
      <div class="ant-col-5 ant-form-item-label">
        <label>Summary</label>
      </div>
      <div class="ant-col-15 ant-form-item-control">
        <a-textarea class="ant-input" placeholder="Enter summary of post" :autosize="{ minRows: 2, maxRows: 6 }" />
      </div>
    </div>
    <div class="ant-row ant-form-item">
      <div class="ant-col-5 ant-form-item-label">
        <label>Summary</label>
      </div>
      <div class="ant-col-15 ant-form-item-control">
        <!-- <tiny-mce></tiny-mce> -->
        <editor id="nsg5a998nolh72tlafkx9ah702obzpq1leytofu5dbnqie98"
          toolbar1="undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment"
          :other_options="other_options"
          ></editor>
      </div>
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
      other_options: {
        file_browser_callback: function(field_name, url, type, win) {
            tinymce.activeEditor.windowManager.open({
                file: '/admin/finder/browse',
                title: 'window.language_lib.manage_image',
                width: 900,  
                height: 550,
                resizable: 'yes'
            }, {
                setUrl: function (url) {
                    win.document.getElementById(field_name).value = url;
                }
            });
        }
      }
    }
  },
  methods: {
    save() {
      console.log('123')
    }
  }
}
</script>
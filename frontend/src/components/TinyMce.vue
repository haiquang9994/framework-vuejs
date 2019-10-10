<template>
  <tinymce-editor :id="key" :toolbar1="toolbar1" :other_options="other_options"></tinymce-editor>
</template>

<script>
import store from "@/store";

export default {
  name: "TinyMce",
  data() {
    return {
      key: "nsg5a998nolh72tlafkx9ah702obzpq1leytofu5dbnqie98",
      toolbar1:
        "undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media pageembed template link anchor codesample | a11ycheck ltr rtl | showcomments addcomment",
      other_options: {
        file_browser_callback(field_name, url, type, win) {
          let activeUrl = "";
          tinymce.activeEditor.windowManager.open(
            {
              file: "/admin/finder/popup",
              title: "File Manager",
              width: 900,
              height: 550,
              resizable: "yes",
              buttons: [
                {
                  text: "Insert",
                  onclick() {
                    if (activeUrl) {
                      win.document.getElementById(field_name).value = activeUrl;
                    }
                    tinymce.activeEditor.windowManager.close();
                  }
                },
                {
                  text: "Close",
                  onclick: "close"
                }
              ]
            },
            {
              setUrl(url) {
                win.document.getElementById(field_name).value = url;
              },
              setActiveUrl(url) {
                activeUrl = url;
              }
            }
          );
        },
        height: '300px'
      },
    };
  }
};
</script>
<template>
  <div class="file-manager">
    <div class="fm-tools">
      <ul>
        <li>
          <a-icon type="caret-up" />
        </li>
        <li>
          <a-icon type="folder-add" />
        </li>
        <li>
          <a-icon type="download" />
        </li>
        <li>
          <a-icon type="upload" />
        </li>
        <li>
          <a-icon type="eye" />
        </li>
      </ul>
    </div>
    <div class="fm-contents">
      <div class="fm-tree">
        <direction :directories="directories" @click="clickDirectory" :data="data" />
      </div>
      <div class="fm-files">
        <div class="fm-file" v-for="item in folders" :key="item.basename" @dblclick="listContents(item.path)">
          <a-icon class="fm-folder-img" type="folder" />
          <span class="fm-file-name">{{ item.basename }}</span>
        </div>
        <div class="fm-file" v-for="item in files" :key="item.basename" @dblclick="chooseItem(item)" @click="selectItem(item)" :class="{active: data.item_active == item.filename}">
          <span
            class="fm-file-img"
            :style="{backgroundImage: 'url(\'' + item.thumb + '\')'}"
          ></span>
          <span class="fm-file-name">{{ item.basename }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import Direction from './components/Directory'
const connector = 'http://framework.lc/api/admin/finder/connector'

export default {
  data() {
    return {
      items: [],
      folders: [],
      files: [],
      directories: [ { path: '.', name: '.', label: 'Root', children: [] } ],
      data: {
        active: '',
        item_active: '',
      }
    }
  },
  components: {
    Direction
  },
  mounted() {
    this.listContents('.')
    window.name = 'Nguyen Hai Quang'
  },
  methods: {
    selectItem(item) {
      this.data.item_active = item.filename
      window.active_url = item.url
      let params = window.parent.tinymce.activeEditor.windowManager.getParams()
      let setUrl = params.setActiveUrl(item.url)
    },
    chooseItem(item) {
      let params = window.parent.tinymce.activeEditor.windowManager.getParams()
      let setUrl = params.setUrl(item.url)
      // console.log(window.field_name)
      // let input = window.parent.document.getElementById('mceu_72' + '-inp')
      // input.value = item.url
      window.parent.tinymce.activeEditor.windowManager.close()
      // console.log(window.parent.tinymce.activeEditor.windowManager.getParams())
    },
    clickDirectory(item) {
      this.listContents(item.path)
    },
    listContents(path) {
      this.data.active = path
      let formData = new FormData()
      formData.append('command', 'open_dir')
      formData.append('path', path)
      axios.post(connector + '?_token=' + this.$store.state.token, formData, {
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded'
        }
      }).then(response => {
        if (response.status === 200) {
          this.folders = response.data.data.folders
          this.files = response.data.data.files
          this.appendFolders(path, response.data.data.folders)
        }
      })
    },
    findDir(path, parent) {
      if (!parent) {
        parent = this.directories
      }
      let list = path.split('/')
      let f = list.shift()
      let current = parent.find(item => item.name === f)
      if (current) {
        if (list.length === 0) {
          return current
        }
        return this.findDir(list.join('/'), current.children)
      }
      return null
    },
    appendFolders(path, folders) {
      let directory = this.findDir(path, null)
      if (directory instanceof Object && directory.children instanceof Array) {
        folders.forEach(folder => {
          if (!directory.children.find(d => d.name === folder.filename)) {
            directory.children.push({ path: folder.path, name: folder.filename, label: folder.filename, children: [] })
          }
        })
      }
    }
  }
};
</script>

<style lang="scss">
.fm-folder-img {
  height: 60px;
  svg {
    height: 60px;
    width: 60px;
  }
}
.file-manager {
  position: fixed;
  top: 0;
  right: 0;
  left: 0;
  bottom: 0;
  overflow: auto;
}
.fm-tools {
  padding: 6px 10px;
  border-bottom: 1px solid #011528;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  background-color: #fff;
  z-index: 1;
  ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    li {
      height: 24px;
      width: 24px;
      font-size: 16px;
      padding: 0;
      border: 1px solid #000;
      text-align: center;
      margin: 0 10px 0 0;
      border-radius: 3px;
      cursor: pointer;
      user-select: none;
      color: #fff;
      background-color: #011528;
      &:active {
        opacity: 0.7;
      }
    }
  }
}
.fm-contents {
  position: absolute;
  top: 36px;
  left: 0;
  right: 0;
  bottom: 0;
  .fm-tree {
    position: fixed;
    width: 250px;
    top: 36px;
    bottom: 0;
    left: 0;
    background-color: #011528;
    color: #fff;
    padding: 5px;
    ul {
      padding: 0;
      list-style: none;
      ul {
        margin-left: 8px;
      }
      li {
        span {
          user-select: none;
          cursor: pointer;
          display: block;
          padding: 2px 4px;
          border-radius: 3px;
          margin: 0 0 3px;
          &:hover {
            background-color: #838383;
          }
          &.active {
            background-color: #838383;
          }
        }
      }
    }
  }
  .fm-files {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 250px;
    padding: 0;
    margin: 10px 0 10px 8px;
    display: flex;
    flex-wrap: wrap;
    align-content: flex-start;
    .fm-file {
      display: flex;
      flex-direction: column;
      width: 120px;
      margin: 0 5px 10px 0;
      padding: 5px 0;
      text-align: center;
      border-radius: 5px;
      user-select: none;
      cursor: pointer;
      transition: background-color 200ms;
      .fm-file-img {
        width: 60px;
        height: 60px;
        display: inline-block;
        margin: 0 auto;
      }
      &:hover {
        background-color: #dadada;
      transition: background-color 0s;
      }
      &.active {
        background-color: #ebebeb;
      }
    }
  }
}
</style>

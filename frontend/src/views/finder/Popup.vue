<template>
    <div class="file-manager">
        <div class="fm-tools">
            <ul>
                <li :class="{disabled: data.parent_folder === null}" @click="listParentContents()">
                    <a-icon type="caret-up" />
                </li>
                <li @click="openNewFolder">
                    <a-icon type="folder-add" />
                </li>
                <li>
                    <a-icon type="cloud-download" />
                </li>
                <li @click="openUpload">
                    <a-icon type="file-add" />
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
            <div class="fm-files" @click="outSelectFiles">
                <div
                    class="fm-file"
                    v-for="item in folders"
                    :key="item.basename"
                    @dblclick="listContents(item.path)"
                >
                    <a-icon class="fm-folder-img" type="folder" />
                    <span class="fm-file-name">{{ item.basename }}</span>
                </div>
                <div
                    class="fm-file"
                    v-for="item in files"
                    :key="item.basename"
                    @dblclick="chooseItem(item)"
                    @click="selectItem(item)"
                    :title="item.basename"
                    :class="{active: data.selected.indexOf(item.url) > -1, new_item: new_items.indexOf(item.path) > -1}"
                >
                    <span
                        class="fm-file-img"
                        :style="{backgroundImage: 'url(\'' + item.thumb + '\')'}"
                    ></span>
                    <span class="fm-file-name">{{ item.basename }}</span>
                </div>
            </div>
        </div>
        <div class="fm-actions" :class="{active: actions.show}">
            <span class="fm-actions-fog" @click="closePopup"></span>
            <div class="fm-new-folder" :class="{active: actions.new_folder.show}">
                <input type="text" v-model="actions.new_folder.name" ref="input_new_folder_name">
                <div class="mce-btn">
                    <button @click="createFolder">Create</button>
                </div>
            </div>
            <div class="fm-upload-action" :class="{active: actions.upload.show}">
                <label>
                    <input type="file" @change="submitUpload" multiple>
                    <span>Select files</span>
                </label>
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
            new_items: [],
            directories: [
                { path: '.', name: '.', label: 'Root', children: null }
            ],
            actions: {
                show: false,
                new_folder: {
                    show: false,
                    name: ''
                },
                upload: {
                    show: false,
                    files: []
                }
            },
            data: {
                multiple: false,
                active: '',
                selected: [],
                parent_folder: null
            }
        }
    },
    components: {
        Direction
    },
    mounted() {
        let path = this.$store.state.__finder_dir || '.'
        this.listContents(path)
    },
    methods: {
        outSelectFiles(e) {
            if (e.target.classList.contains('fm-files')) {
                this.data.selected = []
            }
        },
        submitUpload(e) {
            let formData = new FormData()
            formData.append('command', 'upload')
            formData.append('path', this.data.active)
            let files = e.target.files
            for (let i = 0; i < files.length; i++) {
                formData.append('file-' + i, files[i])
            }
            e.target.value = ''
            this.data.selected = []
            this.closePopup()
            axios
                .post(
                    connector + '?_token=' + this.$store.state.token,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }
                )
                .then(response => {
                    if (response.status === 200) {
                        if (response.data.data instanceof Array) {
                            response.data.data.forEach(item => {
                                this.files.unshift(item)
                                this.new_items.push(item.path)
                            })
                        }
                    }
                })
        },
        createFolder() {
            let path = this.data.active + '/' + this.actions.new_folder.name
            let formData = new FormData()
            formData.append('command', 'create_dir')
            formData.append('path', path)
            axios
                .post(
                    connector + '?_token=' + this.$store.state.token,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                )
                .then(response => {
                    if (response.status === 200) {
                        this.listContents(path)
                    }
                    this.actions.new_folder.name = ''
                    this.closePopup()
                })
        },
        closePopup() {
            this.actions.show = false
            this.actions.new_folder.show = false
        },
        openNewFolder() {
            this.actions.show = true
            this.actions.new_folder.show = true
            this.$refs.input_new_folder_name.focus()
        },
        openUpload() {
            this.actions.show = true
            this.actions.upload.show = true
        },
        selectItem(item) {
            if (!this.data.multiple) {
                this.data.selected = []
            }
            if (this.data.selected.indexOf(item.url) === -1) {
                this.data.selected.push(item.url)
                this.data.item_active = item.filename
                let params = window.parent.tinymce.activeEditor.windowManager.getParams()
                let setUrl = params.setActiveUrl(item.url)
            } else {
                let i = this.data.selected.indexOf(item.url)
                this.data.selected.splice(i, 1)
            }
        },
        chooseItem(item) {
            let params = window.parent.tinymce.activeEditor.windowManager.getParams()
            let setUrl = params.setUrl(item.url)
            window.parent.tinymce.activeEditor.windowManager.close()
        },
        clickDirectory(item) {
            this.listContents(item.path)
        },
        listParentContents() {
            if (this.data.parent_folder !== null) {
                this.listContents(this.data.parent_folder)
            }
        },
        findParentPath(path) {
            if (path === '.') {
                return null
            }
            let list = path.split('/')
            list.pop()
            return list.join('/')
        },
        listContents(path) {
            this.$c('__finder_dir', path, true)
            this.data.parent_folder = this.findParentPath(path)
            this.data.active = path
            let formData = new FormData()
            formData.append('command', 'open_dir')
            formData.append('path', path)
            axios
                .post(
                    connector + '?_token=' + this.$store.state.token,
                    formData,
                    {
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        }
                    }
                )
                .then(response => {
                    if (response.status === 200) {
                        this.folders = response.data.data.folders
                        this.files = response.data.data.files
                        this.appendFolders(path, response.data.data.root, response.data.data.folders)
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
        appendRootFolders(folders, path) {
            let directory = this.findDir(path || '.')
            if (!(directory.children instanceof Array)) {
                directory.children = []
            }
            folders.forEach(folder => {
                if (
                    !directory.children.find(
                        d => d.name === folder.filename
                    )
                ) {
                    directory.children.push({
                        path: folder.path,
                        name: folder.filename,
                        label: folder.filename,
                        children: null
                    })
                }
                if (folder.children instanceof Array) {
                    this.appendRootFolders(folder.children, folder.path)
                }
            })
        },
        appendFolders(path, root_folders, folders) {
            this.appendRootFolders(root_folders)
            let directory = this.findDir(path, null)
            if (
                directory instanceof Object &&
                directory.children instanceof Array
            ) {
                folders.forEach(folder => {
                    if (
                        !directory.children.find(
                            d => d.name === folder.filename
                        )
                    ) {
                        directory.children.push({
                            path: folder.path,
                            name: folder.filename,
                            label: folder.filename,
                            children: null
                        })
                    }
                })
            }
        }
    }
}
</script>

<style lang="scss">
@keyframes new_item {
    0% {
        opacity: 0;
    }
    20% {
        opacity: 1;
    }
    40% {
        opacity: 0;
    }
    60% {
        opacity: 1;
    }
    80% {
        opacity: 0;
    }
    100% {
        opacity: 1;
    }
}
.fm-actions {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: -1;
    opacity: 0;
    background-color: rgba(0,0,0,0.6);
    transition: z-index 0s 200ms, opacity 200ms;
    &.active {
        z-index: 2;
        opacity: 1;
        transition: z-index 0s, opacity 200ms;
    }
    .fm-actions-fog {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
    .fm-upload-action {
        position: absolute;
        width: 200px;
        background-color: #fff;
        left: calc(50% - 100px);
        padding: 10px;
        border-radius: 0 0 3px 3px;
        text-align: center;
        opacity: 0;
        z-index: -1;
        &.active {
            opacity: 1;
            z-index: auto;
        }
        label {
            padding: 10px 15px;
            display: inline-block;
            border: 1px solid #c7c7c7;
            cursor: pointer;
            user-select: none;
            &:active {
                opacity: 0.7;
            }
            input {
                display: none;
            }
        }
    }
    .fm-new-folder {
        position: absolute;
        width: 200px;
        background-color: #fff;
        left: calc(50% - 100px);
        padding: 10px;
        border-radius: 0 0 3px 3px;
        text-align: center;
        opacity: 0;
        z-index: -1;
        &.active {
            opacity: 1;
            z-index: auto;
        }
        input {
            outline: none;
            border: 1px solid #c7c7c7;
            padding: 2px 10px;
            width: 100%;
            text-align: left;
        }
        .mce-btn {
            margin-top: 8px;
        }
        button {
            outline: none;
        }
    }
}
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
            &.disabled {
                opacity: 0.2;
                cursor: unset;
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
    background-color: #fff;
    .fm-tree {
        position: fixed;
        width: 250px;
        top: 36px;
        bottom: 0;
        left: 0;
        background-color: #011528;
        color: #fff;
        padding: 5px;
        overflow: auto;
        ul {
            padding: 0;
            list-style: none;
            ul {
                margin-left: 10px;
            }
            li {
                position: relative;
                i {
                    position: absolute;
                    font-style: normal;
                    left: 4px;
                    top: 1px;
                }
                span {
                    user-select: none;
                    cursor: pointer;
                    display: block;
                    padding: 2px 4px 2px 15px;
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
            padding: 5px;
            text-align: center;
            border-radius: 5px;
            user-select: none;
            cursor: pointer;
            transition: background-color 200ms;
            .fm-file-name {
                line-height: 18px;
                max-height: 54px;
                overflow: hidden;
            }
            .fm-file-img {
                width: 60px;
                height: 60px;
                display: inline-block;
                margin: 0 auto 10px;
                background-position: center;
                background-repeat: no-repeat;
                background-size: contain;
                border: 1px solid #b5b5b5;
                border-radius: 3px;
                background-color: #fff;
            }
            &:hover {
                background-color: #ebebeb;
                transition: background-color 0s;
            }
            &.active {
                background-color: #dadada;
            }
            &.new_item {
                animation: new_item 1500ms;
            }
        }
    }
}
</style>

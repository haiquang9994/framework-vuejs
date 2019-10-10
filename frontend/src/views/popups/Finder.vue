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
                <ul>
                    <li>
                        <span>Root</span>
                        <ul>
                            <li>
                                <span>Folder 1</span>
                            </li>
                            <li>
                                <span>Folder 2</span>
                            </li>
                            <li>
                                <span>Folder 3</span>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="fm-files">
                <div class="fm-file" v-for="n in 100" :key="n">
                    <span class="fm-file-img" :style="{backgroundImage: 'url(https://picsum.photos/id/' + n +  '/300/300)'}"></span>
                    <span class="fm-file-name">Image {{ n }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            directories: {}
        }
    }
}
</script>

<style lang="scss">
.file-manager {
    border: 1px solid #b5b5b5;
    border-radius: 5px;
}
.fm-tools {
    padding: 6px 10px;
    border-bottom: 1px solid #b5b5b5;
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
    display: flex;
    .fm-tree {
        height: 500px;
        width: 250px;
        background-color: #011528;
        color: #fff;
        border-radius: 0 0 0 5px;
        padding: 5px;
        ul {
            padding: 0;
            list-style: none;
            ul {
                margin-left: 12px;
            }
            li {
                span {
                    user-select: none;
                    cursor: pointer;
                    display: block;
                    padding: 2px 8px;
                    border-radius: 3px;
                    &:hover {
                        background-color: #838383;
                    }
                }
            }
        }
    }
    .fm-files {
        width: calc(100% - 250px);
        max-height: 500px;
        overflow: auto;
        padding: 10px;
        display: flex;
        flex-wrap: wrap;
        .fm-file {
            display: flex;
            flex-direction: column;
            width: 120px;
            margin: 0 5px 5px 0;
            padding: 5px 0;
            text-align: center;
            border-radius: 5px;
            user-select: none;
            .fm-file-img {
                width: 60px;
                height: 60px;
                display: inline-block;
                margin: auto;
            }
            &:hover {
                background-color: #dadada;
            }
        }
    }
}
</style>

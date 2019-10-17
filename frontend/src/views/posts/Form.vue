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
                <a-input class="ant-input" v-model="item.title" placeholder="Enter title of post" />
            </a-col>
        </a-row>
        <a-row class="ant-form-item">
            <a-col :lg="{ span: 3 }" class="ant-form-item-label">
                <label>Summary</label>
            </a-col>
            <a-col :lg="{ span: 18 }" class="ant-form-item-control">
                <a-textarea
                    class="ant-input"
                    v-model="item.summary"
                    placeholder="Enter summary of post"
                    :autosize="{ minRows: 3, maxRows: 6 }"
                />
            </a-col>
        </a-row>
        <a-row class="ant-form-item">
            <a-col :lg="{ span: 3 }" class="ant-form-item-label">
                <label>Content</label>
            </a-col>
            <a-col :lg="{ span: 18 }" class="ant-form-item-control">
                <tiny-mce v-model="item.content"></tiny-mce>
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
                <a-radio-group name="radioGroup" v-model="item.active">
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
                <a-radio-group name="radioGroup" v-model="item.featured">
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
    name: 'PostForm',
    components: {
        PageTitle,
        TinyMce
    },
    data() {
        return {
            mode: 'create',
            item: {
                title: '',
                summary: '',
                content: `<p>Quang</p>`,
                published_at: {
                    date: moment('2019-10-15 12:30:00', 'YYYY-MM-DD HH:mm:ss'),
                    time: moment('2019-10-15 12:30:00', 'YYYY-MM-DD HH:mm:ss')
                },
                active: 1,
                featured: 0,
            }
        }
    },
    mounted() {
        if (this.$route.name === 'post_new') {
            this.mode = 'create'
        } else {
            this.mode = 'update'
            this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post/' + this.$route.params.id)
                .authed(this.$store.state.token)
                .sent()
                .then(response => {
                    if (response.status) {
                        let data = response.data
                        this.item.title = data.title
                        this.item.summary = data.summary
                        this.item.content = data.content
                        this.item.published_at = {
                            date: moment(data.published_at, 'YYYY-MM-DD HH:mm:ss'),
                            time: moment(data.published_at, 'YYYY-MM-DD HH:mm:ss')
                        }
                        this.item.active = data.active ? 1 : 0
                        this.item.featured = data.featured ? 1 : 0
                    }
                })
        }
    },
    methods: {
        moment,
        save() {
            let data = {
                title: this.item.title,
                summary: this.item.summary,
                content: this.item.content,
                published_at: this.item.published_at.date.format('YYYY-MM-DD') + ' ' + this.item.published_at.time.format('HH:mm:00'),
                active: this.item.active,
                featured: this.item.featured,
            }
            if (this.mode === 'create') {
                this.saveNew(data)
            } else {
                this.saveUpdate(data)
            }
        },
        saveNew(data) {
            let rq = this.$http.post(process.env.VUE_APP_API_ENDPOINT + 'post')
                .withBody(data)
                .authed(this.$store.state.token)
                .sent()
                .then(response => {
                    if (response.status) {
                        this.$store.state.tmp.reload_first_page = true
                        this.$replaceActiveTab('/posts', true)
                    }
                })
                .catch(e => {
                })
        },
        saveUpdate(data) {
            let rq = this.$http.put(process.env.VUE_APP_API_ENDPOINT + 'post/' + this.$route.params.id)
                .withBody(data)
                .authed(this.$store.state.token)
                .sent()
                .then(response => {
                    if (response.status) {
                        this.$store.state.tmp.reload = true
                        this.$replaceActiveTab('/posts', true)
                    }
                })
                .catch(e => {
                })
        }
    }
}
</script>
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
    name: 'NewPost',
    components: {
        PageTitle,
        'tiny-mce': TinyMce
    },
    data() {
        return {
            item: {
                title: 'This is a title',
                summary: 'This is a summary',
                content: `<p>fgsdf gsdf gsd fgsd fgs dfg sdfg sdfg&nbsp;</p>
<p>dfsgsd fgs dfgs dfg sdfg sdfg&nbsp;</p>
<p>asdfasd fas dfa sdf asdf asdf asdf a</p>
<p>gsd fgsd fgs dfgs dfgs dfg&nbsp;</p>
<hr /><hr />
<p>&nbsp;</p>`,
                published_at: {
                    date: moment('2019-10-15 12:30:00', 'YYYY-MM-DD HH:mm:ss'),
                    time: moment('2019-10-15 12:30:00', 'YYYY-MM-DD HH:mm:ss')
                },
                active: 1,
                featured: 0,
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
                published_at: this.item.published_at.date.format('YYYY-MM-DD') + ' ' + this.item.published_at.time.format('HH:mm:00'),
                active: this.item.active,
                featured: this.item.featured,
            }
            let rq = this.$http.post(process.env.VUE_APP_API_ENDPOINT + 'post')
                .withBody(data)
                .authed(this.$store.state.token)
                .sent()
                .then(response => {
                    this.$router.push('/posts/' + response.id)
                    console.log(response)
                })
                .catch(e => {
                    console.log(e)
                })
        }
    }
}
</script>
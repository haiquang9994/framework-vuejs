<template>
    <div>
        <page-title title="New Post">
            <a-button :loading="processing" type="primary" @click="save">Save</a-button>
        </page-title>
        <a-form-item
            label="Title"
            :label-col="{ lg: 3 }"
            :wrapper-col="{ lg: 18 }"
            :validate-status="validator.title.status"
            :help="validator.title.help"
        >
            <a-input
                @blur="$validate(validator.title, item.title)"
                v-model="item.title"
                placeholder="Enter title of post"
            />
        </a-form-item>
        <a-form-item label="Image" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <upload-file @change="changeImage" />
            <span class="small-space"></span>
            <select-file @change="changeImage" />
            <div>
                <img
                    class="form-thumbnail"
                    v-if="item.image.thumbUrl"
                    :src="item.image.thumbUrl"
                    alt="avatar"
                />
            </div>
        </a-form-item>
        <a-form-item label="Summary" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-textarea
                class="ant-input"
                v-model="item.summary"
                placeholder="Enter summary of post"
                :autosize="{ minRows: 3, maxRows: 6 }"
            />
        </a-form-item>
        <a-form-item label="Content" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <tiny-mce v-model="item.content"></tiny-mce>
        </a-form-item>
        <a-form-item label="Publish at" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-date-picker format="DD/MM/YYYY" v-model="item.published_at.date" />
            <span class="small-space"></span>
            <a-time-picker format="HH:mm" v-model="item.published_at.time" />
        </a-form-item>
        <a-form-item label="Active" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-radio-group name="radioGroup" v-model="item.active">
                <a-radio :value="1">Yes</a-radio>
                <a-radio :value="0">No</a-radio>
            </a-radio-group>
        </a-form-item>
        <a-form-item label="Featured" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-radio-group name="radioGroup" v-model="item.featured">
                <a-radio :value="1">Yes</a-radio>
                <a-radio :value="0">No</a-radio>
            </a-radio-group>
        </a-form-item>
        <a-form-item :wrapper-col="{ lg: 18, offset: 3 }">
            <a-button :loading="processing" type="primary" @click="save">Save</a-button>
        </a-form-item>
    </div>
</template>

<script>
import moment from 'moment'
import validator from 'validator'

export default {
    data() {
        return {
            mode: this.$route.name === 'post_new' ? 'create' : 'update',
            processing: false,
            validator: {
                title: {
                    status: '',
                    help: '',
                    handle(value) {
                        return validator.isLength(value, { min: 1, max: 255 })
                    },
                    message: 'Title has length between 1 and 255!'
                },
            },
            item: {
                title: '',
                summary: '',
                content: ``,
                published_at: {
                    date: moment('2019-10-15 12:30:00', 'YYYY-MM-DD HH:mm:ss'),
                    time: moment('2019-10-15 12:30:00', 'YYYY-MM-DD HH:mm:ss')
                },
                active: 1,
                featured: 0,
                image: {
                    url: '',
                    thumbUrl: '',
                },
            },
        }
    },
    methods: {
        changeImage(url) {
            this.item.image.url = this.item.image.thumbUrl = url
        },
        save() {
            let data = {
                title: this.item.title,
                summary: this.item.summary,
                content: this.item.content,
                published_at: this.item.published_at.date.format('YYYY-MM-DD') + ' ' + this.item.published_at.time.format('HH:mm:00'),
                active: this.item.active,
                featured: this.item.featured,
                image: this.item.image.url,
            }
            if (this.mode === 'create') {
                this.saveNew(data)
            } else {
                this.saveUpdate(data)
            }
        },
        saveNew(data) {
            if (this.$validate_all(this.validator, this.item)) {
                this.processing = true
                this.$http.post(process.env.VUE_APP_API_ENDPOINT + 'post')
                    .withBody(data)
                    .authed(this.$store.state.token)
                    .sent()
                    .then(response => {
                        if (response.status) {
                            this.$store.state.tmp.reload_first_page = true
                            this.$replaceActiveTab('/posts', true)
                        }
                        this.processing = false
                    })
                    .catch(e => {
                        this.processing = false
                        this.$log(e)
                    })
            }
        },
        saveUpdate(data) {
            if (this.$validate_all(this.validator, this.item)) {
                this.processing = true
                this.$http.put(process.env.VUE_APP_API_ENDPOINT + 'post/' + this.$route.params.id)
                    .withBody(data)
                    .authed(this.$store.state.token)
                    .sent()
                    .then(response => {
                        if (response.status) {
                            this.$store.state.tmp.reload = true
                            this.$replaceActiveTab('/posts', true)
                        }
                        this.processing = false
                    })
                    .catch(e => {
                        this.processing = false
                        this.$log(e)
                    })
            }
        }
    },
    mounted() {
        if (this.mode === 'update') {
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
                        this.item.image.url = data.image
                        this.item.image.thumbUrl = data.thumb
                    }
                })
        }
    },
}
</script>
<template>
    <div>
        <page-title :title="title">
            <a-button :loading="processing" type="primary" @click="save">Save</a-button>
        </page-title>
        <a-form-item
            label="Category"
            :label-col="{ lg: 3 }"
            :wrapper-col="{ lg: 18 }"
            :validate-status="validator.category_id.status"
            :help="validator.category_id.help"
        >
            <a-select
                placeholder="Select a category"
                v-model="item.category_id"
                style="min-width: 360px"
                :allowClear="true"
                @change="$validate(validator.category_id, item.category_id)"
            >
                <a-select-option v-for="(label, id) in categories" :key="id.toString()">{{ label }}</a-select-option>
            </a-select>
        </a-form-item>
        <a-form-item
            label="Name"
            :label-col="{ lg: 3 }"
            :wrapper-col="{ lg: 18 }"
            :validate-status="validator.name.status"
            :help="validator.name.help"
        >
            <a-input
                @blur="$validate(validator.name, item.name)"
                v-model="item.name"
                placeholder="Enter name of post"
            />
        </a-form-item>
        <a-form-item label="Image" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <upload-file @change="url => { item.image.url = item.image.thumbUrl = url }" />
            <span class="small-space"></span>
            <select-file @change="url => { item.image.url = item.image.thumbUrl = url }" />
            <div>
                <img
                    class="form-thumbnail"
                    v-if="item.image.thumbUrl"
                    :src="item.image.thumbUrl"
                    alt="avatar"
                />
            </div>
        </a-form-item>
        <a-form-item label="Description" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-textarea
                class="ant-input"
                v-model="item.description"
                placeholder="Enter description of post"
                :autoSize="{ minRows: 3, maxRows: 6 }"
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
                name: {
                    status: '',
                    help: '',
                    handle(value) {
                        return validator.isLength(value, { min: 1, max: 255 })
                    },
                    message: 'Title has length between 1 and 255!'
                },
                category_id: {
                    status: '',
                    help: '',
                    handle(value) {
                        return value !== undefined
                    },
                    message: 'Please select a category!'
                },
            },
            title: 'New Post',
            categories: [],
            item: {
                category_id: undefined,
                name: '',
                description: '',
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
        save() {
            let data = {
                category_id: this.item.category_id,
                name: this.item.name,
                description: this.item.description,
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
                    .authed(this.$token())
                    .sent()
                    .then(response => {
                        if (response.status) {
                            this.$go('/post')
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
                    .authed(this.$token())
                    .sent()
                    .then(response => {
                        if (response.status) {
                            this.$go('/post')
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
                .authed(this.$token())
                .sent()
                .then(response => {
                    if (response.status) {
                        let data = response.data
                        this.item.category_id = data.category_id ? data.category_id.toString() : undefined
                        this.item.name = data.name
                        this.item.description = data.description
                        this.item.content = data.content
                        this.item.published_at = {
                            date: moment(data.published_at, 'YYYY-MM-DD HH:mm:ss'),
                            time: moment(data.published_at, 'YYYY-MM-DD HH:mm:ss')
                        }
                        this.item.active = data.active ? 1 : 0
                        this.item.featured = data.featured ? 1 : 0
                        this.item.image.url = data.image
                        this.item.image.thumbUrl = data.thumb

                        this.title = 'Update Post [' + data.name + ']'
                    }
                })
        }
        this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post/category')
            .authed(this.$token())
            .withBody({ is_options: 1 })
            .sent()
            .then(response => {
                if (response.status) {
                    this.categories = response.data
                }
            })
    },
}
</script>
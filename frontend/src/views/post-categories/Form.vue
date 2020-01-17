<template>
    <div>
        <page-title :title="title">
            <a-button :loading="processing" type="primary" @click="save">Save</a-button>
        </page-title>
        <a-form-item
            label="Parent"
            :label-col="{ lg: 3 }"
            :wrapper-col="{ lg: 18 }"
        >
            <a-select
                placeholder="Select a parent"
                v-model="item.parent_id"
                style="min-width: 360px"
                :allowClear="true"
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
        <a-form-item label="Description" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-textarea
                class="ant-input"
                v-model="item.description"
                placeholder="Enter description of post"
                :autosize="{ minRows: 3, maxRows: 6 }"
            />
        </a-form-item>
        <a-form-item label="Active" :label-col="{ lg: 3 }" :wrapper-col="{ lg: 18 }">
            <a-radio-group name="radioGroup" v-model="item.active">
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
import validator from 'validator'

export default {
    data() {
        return {
            mode: this.$route.name === 'post_category_new' ? 'create' : 'update',
            processing: false,
            validator: {
                name: {
                    status: '',
                    help: '',
                    handle(value) {
                        return validator.isLength(value, { min: 1, max: 255 })
                    },
                    message: 'Name has length between 1 and 255!'
                },
            },
            categories: [],
            title: 'New Post Category',
            item: {
                name: '',
                description: '',
                active: 1,
                parent_id: undefined,
            },
        }
    },
    methods: {
        save() {
            let data = {
                name: this.item.name,
                description: this.item.description,
                active: this.item.active,
                parent_id: this.item.parent_id,
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
                this.$http.post(process.env.VUE_APP_API_ENDPOINT + 'post/category')
                    .withBody(data)
                    .authed(this.$token())
                    .sent()
                    .then(response => {
                        if (response.status) {
                            this.$store.state.tmp.reload_first_page = true
                            this.$replaceActiveTab('/post-categories', true)
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
                this.$http.put(process.env.VUE_APP_API_ENDPOINT + 'post/category/' + this.$route.params.id)
                    .withBody(data)
                    .authed(this.$token())
                    .sent()
                    .then(response => {
                        if (response.status) {
                            this.$store.state.tmp.reload = true
                            this.$replaceActiveTab('/post-categories', true)
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
            this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post/category/' + this.$route.params.id)
                .authed(this.$token())
                .sent()
                .then(response => {
                    if (response.status) {
                        let data = response.data
                        this.item.name = data.name
                        this.item.description = data.description
                        this.item.parent_id = data.parent_id ? data.parent_id.toString() : undefined
                        this.item.active = data.active ? 1 : 0

                        this.title = 'Update Post Category [' + data.name + ']'
                    }
                })
        }
        this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post/category')
            .authed(this.$token())
            .withBody({ is_options: 1, parent_for: this.$route.params.id })
            .sent()
            .then(response => {
                if (response.status) {
                    this.categories = response.data
                }
            })
    },
}
</script>
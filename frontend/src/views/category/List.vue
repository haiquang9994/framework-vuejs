<template>
    <div>
        <page-title title="Category">
            <text-link type="primary" title="New" href="/category/new" />
        </page-title>
        <a-table
            :columns="columns"
            :rowKey="(record) => record.id"
            :dataSource="data"
            :pagination="pagination"
            :loading="loading"
            @change="handleTableChange"
        >
            <span slot="image" slot-scope="record">
                <img :src="record.image" :alt="record.title" />
            </span>
            <div slot="action" slot-scope="record" class="table-record-actions">
                <icon-link
                    size="small"
                    icon="edit"
                    title="Update"
                    :href="'/category/' + record.id"
                />
                <span class="small-space"></span>
                <icon-link
                    size="small"
                    icon="bars"
                    title="Children"
                    :href="'/category?parent_id=' + record.id"
                />
                <span class="small-space"></span>
                <icon-link
                    size="small"
                    icon="appstore"
                    title="List post"
                    :href="'/post?cate_id=' + record.id"
                />
                <span class="small-space"></span>
                <a-popconfirm
                    title="Are you sure delete this record?"
                    @confirm="confirm(record)"
                    okText="Yes"
                    cancelText="No"
                >
                    <a-button size="small" title="Delete">
                        <fai :icon="['fas', 'trash']" />
                    </a-button>
                </a-popconfirm>
            </div>
        </a-table>
    </div>
</template>

<script>
import queryString from 'query-string'
import ResizeTable from '@/mixins/ResizeTable'

export default {
    data() {
        return {
            loading: true,
            columns: [
                {
                    title: 'Name',
                    dataIndex: 'name',
                    sorter: true,
                    width: '30%'
                },
                {
                    title: 'Description',
                    dataIndex: 'description',
                },
                {
                    title: 'Action',
                    key: 'action',
                    scopedSlots: { customRender: 'action' },
                },
            ],
            data: [],
            pagination: {
                total: 0,
                pageSize: 0,
                position: 'top',
            },
            currentPage: 1,
            sorter: {
                field: null,
                order: null
            }
        }
    },
    methods: {
        handleTableChange(pagination, filters, sorter) {
            this.currentPage = pagination.current
            this.sorter.field = sorter.field || null
            this.sorter.order = sorter.order ? (sorter.order === 'ascend' ? 'asc' : 'desc') : null
            this.pull(pagination.current)
        },
        pull(page) {
            let vm = this
            vm.loading = true
            let data = {
                _page: page
            }
            if (this.sorter.field) {
                data._orderBy = this.sorter.field + '.' + this.sorter.order
            }
            let params = queryString.parse(location.href.substring(location.href.indexOf('?')))
            data._params = params
            this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post/category')
                .authed(this.$token())
                .withBody(data)
                .sent()
                .then(response => {
                    if (response.status) {
                        this.data = response.data
                        this.pagination.total = response.pagination.total
                        this.pagination.pageSize = response.pagination.pageSize
                    }
                    setTimeout(function () {
                        vm.loading = false
                    }, 200)
                })
        },
        confirm(record) {
            this.$http.delete(process.env.VUE_APP_API_ENDPOINT + 'post/category/' + record.id)
                .authed(this.$token())
                .sent()
                .then(response => {
                    if (response.status) {
                        this.pull(this.currentPage)
                    }
                })
        },
    },
    mounted() {
        this.pull(1)
    },
    mixins: [
        ResizeTable
    ],
};
</script>
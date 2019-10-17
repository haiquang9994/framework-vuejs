<template>
    <div>
        <page-title title="Posts">
            <router-link class="ant-btn ant-btn-primary" to="/posts/new">New</router-link>
        </page-title>
        <a-table
            :columns="columns"
            :rowKey="record => record.id"
            :dataSource="data"
            :pagination="pagination"
            :loading="loading"
            @change="handleTableChange"
        >
            <div slot="action" slot-scope="record" class="table-record-actions">
                <a-button size="small" icon="edit" @click="$go('/posts/' + record.id)" />
                <span class="small-space"></span>
                <a-popconfirm
                    title="Are you sure delete this record?"
                    @confirm="confirm(record)"
                    okText="Yes"
                    cancelText="No"
                >
                    <a-button size="small">
                        <fai :icon="['fas', 'trash']" />
                    </a-button>
                </a-popconfirm>
            </div>
        </a-table>
    </div>
</template>

<script>
import PageTitle from "@/components/PageTitle"

export default {
    name: 'ListPost',
    components: {
        PageTitle
    },
    data() {
        return {
            loading: true,
            columns: [
                {
                    title: 'Title',
                    dataIndex: 'title',
                    sorter: true,
                },
                {
                    title: 'Summary',
                    dataIndex: 'summary',
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
                pageSize: 0
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
            this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post')
                .authed(this.$store.state.token)
                .withBody(data)
                .sent()
                .then(response => {
                    if (response.status) {
                        this.data = response.data
                        this.pagination.total = response.total
                        this.pagination.pageSize = response.pageSize
                    }
                    setTimeout(function () {
                        vm.loading = false
                    }, 200)
                })
        },
        confirm(record) {
            this.$http.delete(process.env.VUE_APP_API_ENDPOINT + 'post/' + record.id)
                .authed(this.$store.state.token)
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
    activated() {
        if (this.$store.state.tmp.reload) {
            this.$store.state.tmp.reload = false
            this.pull(this.currentPage)
        } else if (this.$store.state.tmp.reload_first_page) {
            this.$store.state.tmp.reload_first_page = false
            this.pull(1)
        }
    }
};
</script>
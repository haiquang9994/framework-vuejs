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
            <span slot="action" slot-scope="record">
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
            </span>
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
            currentPage: 1
        }
    },
    methods: {
        handleTableChange(pagination, filters, sorter) {
            this.currentPage = pagination.current
            this.pull(pagination.current)
        },
        pull(page) {
            this.loading = true
            this.$http.get(process.env.VUE_APP_API_ENDPOINT + 'post')
                .authed(this.$store.state.token)
                .withBody({ _page: page })
                .sent()
                .then(response => {
                    this.loading = false
                    if (response.status) {
                        this.data = response.data
                        this.pagination.total = response.total
                        this.pagination.pageSize = response.pageSize
                    }
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
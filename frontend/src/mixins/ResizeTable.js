export default {
    data() {
        return {
            scroll: {
                x: 1050,
                y: 0,
            }
        }
    },
    methods: {
        windowResize() {
            let wrap = document.querySelector('.router-view-wrap')
            let title = document.querySelector('.page-title')
            if (wrap && title) {
                this.scroll.y = wrap.offsetHeight - title.offsetHeight - 165
            }
        }
    },
    mounted() {
        this.windowResize()
    },
    created() {
        window.addEventListener('resize', this.windowResize)
    },
    destroyed() {
        window.removeEventListener('resize', this.windowResize)
    },
}
import Vue from 'vue'
import Router from 'vue-router'
import multiguard from 'vue-router-multiguard'
import isLogged from './middleware/isLogged'
import isGuest from './middleware/isGuest'
import store from '@/store'

Vue.use(Router)

const routes = {
    '/': ['Dashboard', 'dashboard', { active: 'dashboard', layout: true, label: 'Dashboard' }, [isLogged]],
    '/login': ['Login', 'login', { layout: false, label: 'Login' }, [isGuest]],
    '/setting': ['Setting', 'setting', { active: 'setting', layout: true, label: 'Setting' }, [isLogged]],
    '/profile': ['Profile', 'profile', { active: 'profile', layout: true, label: 'Profile' }, [isLogged]],
    '/finder/popup': ['finder/Popup.vue', 'finder_popup', {}, [isLogged]],

    '/post': ['post/List', 'post_list', { active: 'post', layout: true, label: 'List Post' }, [isLogged]],
    '/post/new': ['post/Form', 'post_new', { active: 'post', layout: true, label: 'New Post' }, [isLogged]],
    '/post/:id': ['post/Form', 'post_update', { active: 'post', layout: true, label: 'Update Post' }, [isLogged]],

    '/category': ['category/List', 'category_list', { active: 'category', layout: true, label: 'List Category' }, [isLogged]],
    '/category/new': ['category/Form', 'category_new', { active: 'category', layout: true, label: 'New Post Category' }, [isLogged]],
    '/category/:id': ['category/Form', 'category_update', { active: 'category', layout: true, label: 'Update Post Category' }, [isLogged]],
}

const vueRoutes = []

for (let path in routes) {
    let options = routes[path]
    vueRoutes.push({
        path,
        component: () => import('@/views/' + options[0]),
        name: options[1],
        meta: options[2] || {},
        beforeEnter: multiguard(options[3] || []),
    })
}

const router = new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: vueRoutes,
})

router.afterEach(route => {
    document.title = 'Admin | ' + route.meta.label
    store.commit('layout.status', route.meta.layout || false)
    if (route.meta.active) {
        store.commit('layout.sidebar.select', route.meta.active)
    }
})

export default router

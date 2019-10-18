import Vue from 'vue'
import Router from 'vue-router'
import multiguard from 'vue-router-multiguard'
import isLoggedIn from './middleware/isLoggedIn'
import isGuest from './middleware/isGuest'

Vue.use(Router)

const routes = {
    '/': ['Dashboard', 'dashboard', { active: 'dashboard', use_layout: true, label: 'Dashboard' }, [isLoggedIn]],
    '/login': ['Login', 'login', { use_layout: false }, [isGuest]],
    '/settings': ['Settings', 'settings', { active: 'settings', use_layout: true, label: 'Settings' }, [isLoggedIn]],
    '/posts': ['posts/List', 'post_list', { active: 'post', use_layout: true, label: 'Posts' }, [isLoggedIn]],
    '/posts/new': ['posts/Form', 'post_new', { active: 'post', use_layout: true, label: 'New Post' }, [isLoggedIn]],
    '/posts/:id': ['posts/Form', 'post_update', { active: 'post', use_layout: true, label: 'Update Post' }, [isLoggedIn]],
    '/profile': ['Profile', 'profile', { active: 'profile', use_layout: true, label: 'Profile' }, [isLoggedIn]],
    '/finder/popup': ['finder/Popup.vue', 'finder_popup', {}, [isLoggedIn]],
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

export default new Router({
    mode: 'history',
    base: process.env.BASE_URL,
    routes: vueRoutes,
})

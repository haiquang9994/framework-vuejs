import Vue from 'vue'
import Router from 'vue-router'
import multiguard from 'vue-router-multiguard'
import isLoggedIn from './middleware/isLoggedIn'
import isGuest from './middleware/isGuest'

Vue.use(Router)

const routes = {
  '/': ['Dashboard', 'dashboard', [isLoggedIn]],
  '/login': ['Login', 'login', [isGuest]],
  '/setting': ['Setting', 'setting', [isLoggedIn]],
  '/profile': ['Profile', 'profile', [isLoggedIn]],
}

const vueRoutes = []

for (let path in routes) {
  let options = routes[path]
  vueRoutes.push({
    path,
    component: () => import('@/views/' + options[0]),
    name: options[1],
    beforeEnter: multiguard(options[2] || []),
  })
}

export default new Router({
  mode: 'history',
  base: process.env.BASE_URL,
  routes: vueRoutes,
})

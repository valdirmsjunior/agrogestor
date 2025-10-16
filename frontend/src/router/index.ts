import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import ProdutoresView from '../views/ProdutoresView.vue'
import { useAuthStore } from '../stores/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/produtores',
      name: 'produtores',
      component: ProdutoresView,
      beforeEnter: (to, from, next) => {
        const authStore = useAuthStore()
        if (authStore.isAuthenticated()) {
          next()
        } else {
          next('/login')
        }
      }
    },
    { path: '/', redirect: '/produtores' }
  ]
})

export default router

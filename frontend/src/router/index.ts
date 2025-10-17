import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import ProdutoresView from '../views/ProdutoresView.vue'
import authGuard from './middleware/auth'

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
      beforeEnter: authGuard
    },
    {
      path: '/produtores/novo',
      name: 'produtores.novo',
      component: () => import('../views/ProdutorFormView.vue'),
      beforeEnter: authGuard
    },
    {
      path: '/produtores/:id/editar',
      name: 'produtores.editar',
      component: () => import('../views/ProdutorFormView.vue'),
      beforeEnter: authGuard
    },
    { path: '/', redirect: '/produtores' }
  ]
})

export default router

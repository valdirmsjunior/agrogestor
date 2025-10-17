import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import ProdutoresView from '../views/ProdutoresView.vue'
import PropriedadesView from '../views/PropriedadesView.vue'
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
    {
      path: '/propriedades',
      name: 'propriedades',
      component: PropriedadesView,
      beforeEnter: authGuard
    },
    {
      path: '/propriedades/novo',
      name: 'propriedades.novo',
      component: () => import('../views/PropriedadeFormView.vue'),
      beforeEnter: authGuard
    },
    {
      path: '/propriedades/:id/editar',
      name: 'propriedades.editar',
      component: () => import('../views/PropriedadeFormView.vue'),
      beforeEnter: authGuard
    },
    {
      path: '/unidades-producao',
      name: 'unidades-producao',
      component: () => import('../views/UnidadesProducaoView.vue'),
      beforeEnter: authGuard
    },
    {
      path: '/unidades-producao/novo',
      name: 'unidades-producao.novo',
      component: () => import('../views/UnidadeProducaoFormView.vue'),
      beforeEnter: authGuard
    },
    {
      path: '/unidades-producao/:id/editar',
      name: 'unidades-producao.editar',
      component: () => import('../views/UnidadeProducaoFormView.vue'),
      beforeEnter: authGuard
    },
    { path: '/', redirect: '/produtores' }
  ]
})

export default router

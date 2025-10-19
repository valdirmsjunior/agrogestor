import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '../views/LoginView.vue'
import ProdutoresView from '../views/ProdutoresView.vue'
import PropriedadesView from '../views/PropriedadesView.vue'
import UnidadesProducaoView from '../views/UnidadesProducaoView.vue'
import RebanhosView from '../views/RebanhosView.vue'
import authGuard from './middleware/auth'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import ProdutorFormView from '../views/ProdutorFormView.vue'
import PropriedadeFormView from '../views/PropriedadeFormView.vue'
import UnidadeProducaoFormView from '../views/UnidadeProducaoFormView.vue'
import RebanhoFormView from '../views/RebanhoFormView.vue'
import RelatoriosView from '../views/RelatoriosView.vue'
import DashboardView from '@/views/DashboardView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DefaultLayout, children: [{ path: '', component:DashboardView}],
      beforeEnter: authGuard
    },
    {
      path: '/produtores',
      name: 'produtores',
      component: DefaultLayout, children: [{ path: '', component:ProdutoresView}],
      beforeEnter: authGuard
    },
    {
      path: '/produtores/novo',
      name: 'produtores.novo',
      component: DefaultLayout, children: [{ path: '', component: ProdutorFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/produtores/:id/editar',
      name: 'produtores.editar',
      component: DefaultLayout, children: [{ path: '', component: ProdutorFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/propriedades',
      name: 'propriedades',
      component: DefaultLayout, children: [{ path: '', component: PropriedadesView}],
      beforeEnter: authGuard
    },
    {
      path: '/propriedades/novo',
      name: 'propriedades.novo',
      component: DefaultLayout, children: [{ path: '', component: PropriedadeFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/propriedades/:id/editar',
      name: 'propriedades.editar',
      component: DefaultLayout, children: [{ path: '', component: PropriedadeFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/unidades-producao',
      name: 'unidades-producao',
      component: DefaultLayout, children: [{ path: '', component: UnidadesProducaoView}],
      beforeEnter: authGuard
    },
    {
      path: '/unidades-producao/novo',
      name: 'unidades-producao.novo',
      component: DefaultLayout, children: [{ path: '', component: UnidadeProducaoFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/unidades-producao/:id/editar',
      name: 'unidades-producao.editar',
      component: DefaultLayout, children: [{ path: '', component: UnidadeProducaoFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/rebanhos',
      name: 'rebanhos',
      component: DefaultLayout, children: [{ path: '', component: RebanhosView}],
      beforeEnter: authGuard
    },
    {
      path: '/rebanhos/novo',
      name: 'rebanhos.novo',
      component: DefaultLayout, children: [{ path: '', component: RebanhoFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/rebanhos/:id/editar',
      name: 'rebanhos.editar',
      component: DefaultLayout, children: [{ path: '', component: RebanhoFormView}],
      beforeEnter: authGuard
    },
    {
      path: '/relatorios',
      name: 'relatorios',
      component: DefaultLayout, children: [{ path: '', component:RelatoriosView}],
      beforeEnter: authGuard
    },
    { path: '/', redirect: '/produtores' }
  ]
})

export default router

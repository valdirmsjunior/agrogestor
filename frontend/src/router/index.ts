import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/LoginView.vue')
    },
    // {
    //   path: '/produtores',
    //   name: 'produtores',
    //   component: () => import('../views/ProdutoresView.vue'),
    //   beforeEnter: authMiddleware
    // }
  ],
})

export default router

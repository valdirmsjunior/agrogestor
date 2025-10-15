import type { NavigationGuardNext, RouteLocationNormalized } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

export default async (
  to: RouteLocationNormalized,
  from: RouteLocationNormalized,
  next: NavigationGuardNext
) => {
  const authStore = useAuthStore()

  if (!authStore.isAuthenticated() && to.name !== 'login') {
    next({ name: 'login' })
  } else if (authStore.isAuthenticated() && to.name === 'login') {
    next({ name: 'produtores' })
  } else {
    next()
  }
}

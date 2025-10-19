<!-- src/components/layout/UserMenu.vue -->
<template>
  <div class="flex items-center space-x-3">
    <span class="text-sm text-gray-700">Olá, {{ authStore.user?.name }}</span>
    <Button
      icon="pi pi-power-off"
      severity="danger"
      text
      @click="handleLogout"
      v-tooltip="'Sair'"
    />
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { useToast } from 'primevue/usetoast'

import Button from 'primevue/button'
const toast = useToast()

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = async () => {
  await authStore.logout()
  toast.add({ severity: 'success', summary: 'Logout realizado', detail: 'Sessão encerrada', life: 3000 })
  router.push('/login')
}
</script>

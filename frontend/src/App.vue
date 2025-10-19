<template>
  <RouterView v-if="route.name === 'login'" />
  <div v-if="authStore.isAuthenticated()" class="flex flex-col min-h-screen">
    <!-- Top Bar -->
    <div class="flex items-center justify-between p-4 bg-white shadow-sm">
      <h1 class="text-xl font-bold text-gray-800">Sistema Agropecuário</h1>
      <UserMenu />
    </div>
    <main class="flex-grow p-6">
      <RouterView />
      <ConfirmDialog />
      <Toast />
    </main>
  </div>
</template>

<script setup lang="ts">
import { useAuthStore } from '../src/stores/auth'
import UserMenu from '../src/components/layout/UserMenu.vue'
import { useRoute } from 'vue-router'
const route = useRoute()
const authStore = useAuthStore()

</script>

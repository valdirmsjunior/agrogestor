<template>
  <div class="flex justify-center items-center min-h-screen bg-gray-50">
    <Card class="w-full max-w-md p-6">
      <template #title>
        <h2 class="text-2xl font-bold text-center text-gray-800">Sistema AgroGestor</h2>
      </template>
      <template #content>
        <form @submit.prevent="handleLogin" class="space-y-4">
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
            <InputText
              id="email"
              v-model="email"
              type="email"
              class="w-full"
              :class="{ 'p-invalid': errors.email }"
              required
            />
            <small v-if="errors.email" class="p-error text-xs">{{ errors.email[0] }}</small>
          </div>

          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Senha</label>
            <InputText
              id="password"
              v-model="password"
              type="password"
              class="w-full"
              :class="{ 'p-invalid': errors.password }"
              required
            />
            <small v-if="errors.password" class="p-error text-xs">{{ errors.password[0] }}</small>
          </div>

          <Button
            type="submit"
            label="Entrar"
            :loading="loading"
            class="w-full mt-4"
            :disabled="loading"
          />
        </form>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'

const email = ref('')
const password = ref('')
const loading = ref(false)
const errors = ref<Record<string, string[]>>({})
const router = useRouter()
const authStore = useAuthStore()

const handleLogin = async () => {
  loading.value = true
  errors.value = {}

  try {
    await authStore.login(email.value, password.value)
    router.push('/produtores')
  } catch (error) {
    const message = extractErrorMessage(error)
  errors.value = { email: [message] }
  } finally {
    loading.value = false
  }
}
</script>

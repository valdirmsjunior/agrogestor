import { ref } from "vue";
import { defineStore } from "pinia";
import api from "@/services/api";

export interface User {
  id: number
  name: string
  email: string
}

export const useAuthStore = defineStore('auth', () => {
  const token = ref<string | null>(localStorage.getItem('token'))
  const stored = localStorage.getItem('user')
  const user = ref<User | null>(stored ? JSON.parse(stored) : null)

  const loading = ref(false)
  const errorMessage = ref<string | null>(null)

  function setAuthData(newToken: string, newUser: User) {
    token.value = newToken
    user.value = newUser
    localStorage.setItem('token', newToken)
    localStorage.setItem('user', JSON.stringify(newUser))
  }

  function clearAuthData() {
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  async function login(email: string, password: string) {
    loading.value = true
    errorMessage.value = null
    try {
      const response = await api.post('/login', { email, password })
      setAuthData(response.data.access_token, response.data.usuario)
    } catch (error: unknown) {
      let message = 'Erro ao fazer login'

      if (error && typeof error === 'object' && 'response' in error) {
        const e = error as { response?: { data?: unknown } }
        const data = e.response?.data

        if (data && typeof data === 'object') {
          const msg = (data as { message?: string }).message
          const emailErrors = (data as { errors?: { email?: string[] } }).errors?.email

          message = msg || (emailErrors && emailErrors[0]) || message
        }
      }
      errorMessage.value = message
      throw error
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    loading.value = true
    try {
      await api.post('/logout')
    } catch (error: unknown) {
      if (error instanceof Error) {
      console.warn('Erro ao fazer logout:', error.message)
    }
    } finally {
      clearAuthData()
      loading.value = false
    }
  }

  const isAuthenticated = () => !!token.value

  return {
    token,
    user,
    loading,
    errorMessage,
    login,
    logout,
    isAuthenticated
  }
})

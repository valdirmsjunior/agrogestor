// src/utils/errorHandler.ts
import type { AxiosError } from 'axios'
import type { ApiError } from '@/types/api'

export function extractErrorMessage(error: unknown): string {
  if (error instanceof Error) {
    return error.message
  }

  if (typeof error === 'object' && error !== null && 'isAxiosError' in error) {
    const axiosError = error as AxiosError<ApiError>

    if (axiosError.response) {
      const data = axiosError.response.data

      if (data?.errors && typeof data.errors === 'object') {
        const firstField = Object.keys(data.errors)[0]
        if (
          firstField &&
          Array.isArray(data.errors[firstField]) &&
          data.errors[firstField].length > 0 &&
          typeof data.errors[firstField][0] === 'string'
        ) {
          return data.errors[firstField][0]
        }
      }

      if (typeof data?.message === 'string') {
        return data.message
      }
    }

    return 'Erro de conexão com o servidor'
  }

  return 'Erro desconhecido'
}

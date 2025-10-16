// src/utils/errorHandler.ts
import type { AxiosError } from 'axios'

export function extractErrorMessage(error: unknown): string {

  if (typeof error === 'object' && error !== null && 'isAxiosError' in error) {
    const axiosError = error as AxiosError

    if (axiosError.response) {
      const data = axiosError.response.data

      if (data && typeof data === 'object' && 'message' in data) {
        const message = data.message
        if (typeof message === 'string') {
          return message
        }
      }

      if (data && typeof data === 'object' && 'errors' in data) {
        const errors = data.errors as Record<string, unknown[]>
        const firstField = Object.keys(errors)[0]
        if (
          firstField &&
          Array.isArray(errors[firstField]) &&
          errors[firstField].length > 0 &&
          typeof errors[firstField][0] === 'string'
        ) {
          return errors[firstField][0]
        }
      }
    }

    return 'Erro ao conectar com o servidor'
  }

  if (error instanceof Error) {
    return error.message
  }

  return 'Erro desconhecido'
}

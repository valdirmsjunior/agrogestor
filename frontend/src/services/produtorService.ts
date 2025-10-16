import api from "@/services/api"
import type { Produtor, ApiPaginatedResponse } from '@/types/produtor'

export const produtorService = {
  async getAll(page: number = 1, filters: Record<string, string> = {}, sort?: { field: string, order: 'asc' | 'desc' }) {
    const params = new URLSearchParams({
      page: page.toString(),
      ...filters
    })

    if (sort) {
      params.append('sort', sort.field)
      params.append('order', sort.order)
    }

    const response = await api.get<ApiPaginatedResponse<Produtor>>(`/produtores?${params}`)
    return response.data
  },

  async delete(id: number) {
    await api.delete(`/produtores/${id}`)
  }
}

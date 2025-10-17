import api from "@/services/api"
import type { Produtor, ApiPaginatedResponse, ProdutorFormData } from '@/types/produtor'

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
  },

  async create(data: ProdutorFormData) {
    const response = await api.post<Produtor>('/produtores', data)
    return response.data
  },

  async update(id: number, data: ProdutorFormData) {
    const response = await api.put<Produtor>(`/produtores/${id}`, data)
    return response.data
  },

  async getById(id: number) {
    const response = await api.get<{ message: string,  data: Produtor }>(`/produtores/${id}`)
    return response.data
  }
}

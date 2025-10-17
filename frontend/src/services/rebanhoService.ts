import api from '@/services/api'
import type { Rebanho, RebanhoFormData } from '@/types/rebanho'
import type { ApiPaginatedResponse } from '@/types/produtor'

export const rebanhoService = {
  async getAll(page: number = 1, filters: Record<string, string> = {}, sort?: { field: string, order: 'asc' | 'desc' }) {
    const params = new URLSearchParams({
      page: page.toString(),
      ...filters
    })

    if (sort) {
      params.append('sort', sort.field)
      params.append('order', sort.order)
    }

    const response = await api.get<ApiPaginatedResponse<Rebanho>>(`/rebanhos?${params}`)
    return response.data
  },

  async create(data: RebanhoFormData) {
    const response = await api.post('/rebanhos', data)
    return response.data
  },

  async update(id: number, data: RebanhoFormData) {
    const response = await api.put(`/rebanhos/${id}`, data)
    return response.data
  },

  async getById(id: number) {
    const response = await api.get<{ message: string, data: Rebanho }>(`/rebanhos/${id}`)
    return response.data.data
  },

  async delete(id: number) {
    await api.delete(`/rebanhos/${id}`)
  }
}

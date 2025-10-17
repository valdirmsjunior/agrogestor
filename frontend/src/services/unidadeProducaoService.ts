import api from '@/services/api'
import type { UnidadeProducao, UnidadeProducaoFormData } from '@/types/unidadeProducao'
import type { ApiPaginatedResponse } from '@/types/produtor'

export const unidadeProducaoService = {
  async getAll(page: number = 1, filters: Record<string, string> = {}, sort?: { field: string, order: 'asc' | 'desc' }) {
    const params = new URLSearchParams({
      page: page.toString(),
      ...filters
    })

    if (sort) {
      params.append('sort', sort.field)
      params.append('order', sort.order)
    }

    const response = await api.get<ApiPaginatedResponse<UnidadeProducao>>(`/unidades-producao?${params}`)
    return response.data
  },

  async create(data: UnidadeProducaoFormData) {
    const response = await api.post('/unidades-producao', data)
    return response.data
  },

  async update(id: number, data: UnidadeProducaoFormData) {
    const response = await api.put(`/unidades-producao/${id}`, data)
    return response.data
  },

  async getById(id: number) {
    const response = await api.get<{ message: string, data: UnidadeProducao }>(`/unidades-producao/${id}`)
    return response.data.data
  },

  async delete(id: number) {
    await api.delete(`/unidades-producao/${id}`)
  }
}

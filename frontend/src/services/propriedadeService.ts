import api from "@/services/api"
import type { Propriedade, PropriedadeFormData } from '@/types/propriedade'
import type { ApiPaginatedResponse } from '@/types/produtor'

export const propriedadeService = {
  async getAll(page: number = 1, filters: Record<string, string> = {}, sort?: { field: string, order: 'asc' | 'desc' }) {
    const params = new URLSearchParams({
      page: page.toString(),
      ...filters
    })

    if (sort) {
      params.append('sort', sort.field)
      params.append('order', sort.order)
    }

    const response = await api.get<ApiPaginatedResponse<Propriedade>>(`/propriedades?${params}`)
    return response.data
  },

  async delete(id: number) {
    await api.delete(`/propriedades/${id}`)
  },

  async create(data: PropriedadeFormData) {
    // const response = await api.post<Propriedade>('/propriedades', data)
    // return response.data
    const payload = {
      ...data,
      area_total: Number(data.area_total)
    }
    const response = await api.post<Propriedade>('/propriedades', payload)
    return response.data
  },

  async update(id: number, data: PropriedadeFormData) {
    const payload = {
      ...data,
      area_total: Number(data.area_total)
    }
    const response = await api.put<Propriedade>(`/propriedades/${id}`, payload)
    return response.data
  },

  async getById(id: number) {
    const response = await api.get<Propriedade >(`/propriedades/${id}`)
    return response.data
  }
}

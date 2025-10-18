import api from '@/services/api'

export interface RelatorioPropriedade {
  municipio: string
  uf: string
  total: number
}

export interface RelatorioEspecie {
  especie: string
  total: number
}

export interface RelatorioCultura {
  nome_cultura: string
  total_ha: number
}

export interface RelatorioResponse {
  propriedades_por_municipio: RelatorioPropriedade[]
  animais_por_especie: RelatorioEspecie[]
  hectares_por_cultura: RelatorioCultura[]
}

export const relatorioService = {
  async getRelatorios() {
    const response = await api.get<RelatorioResponse>('/relatorios')
    return response.data.data
  }
}

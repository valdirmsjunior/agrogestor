export interface UnidadeProducao {
  id: number
  nome_cultura: string
  area_total_ha: number
  coordenadas_geograficas?: { lat: number; lng: number }
  propriedade_id: number
}

export interface UnidadeProducaoFormData {
  nome_cultura: string
  area_total_ha: number
  coordenadas_geograficas?: { lat: number; lng: number }
  propriedade_id: number
}

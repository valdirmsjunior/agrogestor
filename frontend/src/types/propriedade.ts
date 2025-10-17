export interface Propriedade {
  id: number
  nome: string
  municipio: string
  uf: string
  inscricao_estadual: string
  area_total: number
  produtor_id: number
  created_at: string
  updated_at: string
}

export interface PropriedadeFormData {
  nome: string
  municipio: string
  uf: string
  inscricao_estadual?: string
  area_total: number
  produtor_id: number
}

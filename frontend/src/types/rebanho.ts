export interface Rebanho {
  id: number
  especie: string
  quantidade: number
  finalidade: string
  data_atualizacao: string
  propriedade_id: number
}

export interface RebanhoFormData {
  especie: string
  quantidade: number
  finalidade: string
  data_atualizacao?: Date | string | undefined
  propriedade_id: number
}

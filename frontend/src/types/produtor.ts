export interface Produtor {
  id: number
  nome: string
  cpf_cnpj: string
  email: string
  telefone: string
  endereco: string
  data_cadastro: string
  created_at: string
  updated_at: string
}

export interface ApiPaginatedResponse<T> {
  data: T[]
  meta: {
    total: number
    per_page: number
    current_page: number
    last_page: number
  }
}

export interface ProdutorFormData {
  nome: string
  cpf_cnpj: string
  email: string
  telefone: string
  endereco: string
  data_cadastro?: Date | string | undefined
}

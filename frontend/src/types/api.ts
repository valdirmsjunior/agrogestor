
export interface ApiError {
  message?: string
  errors?: Record<string, string[]>
}

export interface ApiResponse<T = unknown> {
  message: string
  data: T
}

import { ref, reactive, readonly, type Ref, toRaw } from 'vue'

interface UseFormOptions<T extends object> {
  initialData: T
  validate?: (data: T) => Record<string, string[]> | null | undefined
}

export function useForm<T extends object>(options: UseFormOptions<T>) {
  const data = reactive({ ...options.initialData }) as T
  const errors: Ref<Record<string, string[]>> = ref({})
  const loading = ref(false)

  const setError = (field: keyof T, message: string) => {
    errors.value = { ...errors.value, [field as string]: [message] }
  }

  const setErrors = (errs: Record<string, string[]>) => {
    errors.value = { ...errs }
  }

  const clearErrors = () => {
    errors.value = {}
  }

  const validate = (): boolean => {
    if (options.validate) {
      const validationErrors = options.validate(toRaw(data))
      if (validationErrors && Object.keys(validationErrors).length > 0) {
        setErrors(validationErrors)
        return false
      }
    }
    clearErrors()
    return true
  }

  const reset = () => {
    Object.assign(data, options.initialData)
    clearErrors()
  }

  return {
    data,
    errors: readonly(errors),
    loading,
    setError,
    setErrors,
    clearErrors,
    validate,
    reset,
  }
}

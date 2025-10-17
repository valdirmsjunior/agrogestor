import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'primevue/usetoast'
import { useForm } from './useForm'
import { produtorService } from '@/services/produtorService'
import type { ProdutorFormData } from '@/types/produtor'
import { extractErrorMessage } from '@/utils/errorHandler'

export function useProdutorForm() {
  const router = useRouter()
  const route = useRoute()
  const toast = useToast()

  const isEditing = computed(() => Boolean(route.params.id))
  const produtorId = computed(() => Number(route.params.id))

  const form = useForm<ProdutorFormData>({
    initialData: {
      nome: '',
      cpf_cnpj: '',
      email: '',
      telefone: '',
      endereco: '',
      data_cadastro: ''
    },
    validate(data: ProdutorFormData) {
      const errors: Record<string, string[]> = {}
      if (!data.nome) errors.nome = ['Nome é obrigatório']
      if (!data.email) errors.email = ['E-mail é obrigatório']
      return Object.keys(errors).length ? errors : null
    }
  })

  const loadProdutor = async () => {
    if (!isEditing.value) return
    try {
      const produtor = await produtorService.getById(produtorId.value)
      Object.assign(form.data, produtor)
    } catch (error) {
      toast.add({ severity: 'error', summary: 'Erro', detail: extractErrorMessage(error), life: 3000 })
      router.push('/produtores')
    }
  }

  const submitForm = async () => {
    if (!form.validate()) return

    form.loading.value = true

    try {
      if (isEditing.value) {
        await produtorService.update(produtorId.value, form.data)
        toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produtor atualizado com sucesso.', life: 3000 })
      } else {
        await produtorService.create(form.data)
        toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produtor criado com sucesso.', life: 3000 })
      }
      router.push('/produtores')
    } catch (error: unknown) {
      if (error instanceof Error) {
        toast.add({ severity: 'error', summary: 'Erro', detail: error.message, life: 3000 })
      } else {
        toast.add({ severity: 'error', summary: 'Erro', detail: extractErrorMessage(error), life: 3000 })
      }
    } finally {
      form.loading.value = false
    }
  }

  return { form, isEditing, submitForm, loadProdutor }
}

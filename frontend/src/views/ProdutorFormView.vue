<template>
  <div class="p-6">
    <h1 class="mb-6 text-2xl font-bold">{{ isEditing ? 'Editar Produtor' : 'Cadastrar Produtor' }}</h1>

    <Card>
      <template #content>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div>
              <label for="nome" class="block mb-1 text-sm font-medium text-gray-700">Nome *</label>
              <InputText
                id="nome"
                v-model="form.nome"
                :class="{ 'p-invalid': errors.nome }"
                required
              />
              <small v-if="errors.nome" class="text-xs p-error">{{ errors.nome[0] }}</small>
            </div>

            <div>
              <label for="cpf_cnpj" class="block mb-1 text-sm font-medium text-gray-700">CPF/CNPJ *</label>
              <InputText
                id="cpf_cnpj"
                v-model="form.cpf_cnpj"
                :class="{ 'p-invalid': errors.cpf_cnpj }"
                required
                maxlength="14"
                inputmode="numeric"
                pattern="[0-9]*"
                @input="justNumber"
                placeholder="Digite somente numeros"
              />
              <small v-if="errors.cpf_cnpj" class="block mt-1 text-xs p-error">{{ errors.cpf_cnpj[0] }}</small>
            </div>

            <div>
              <label for="email" class="block mb-1 text-sm font-medium text-gray-700">E-mail *</label>
              <InputText
                id="email"
                v-model="form.email"
                type="email"
                :class="{ 'p-invalid': errors.email }"
                required
              />
              <small v-if="errors.email" class="block mt-1 text-xs p-error">{{ errors.email[0] }}</small>
            </div>

            <div>
              <label for="telefone" class="block mb-1 text-sm font-medium text-gray-700">Telefone *</label>
              <InputText
                id="telefone"
                v-model="form.telefone"
                :class="{ 'p-invalid': errors.telefone }"
                required
              />
              <small v-if="errors.telefone" class="text-xs p-error">{{ errors.telefone[0] }}</small>
            </div>

            <div class="md:col-span-2">
              <label for="endereco" class="block mb-1 text-sm font-medium text-gray-700">Endereço *</label>
              <Textarea
                id="endereco"
                v-model="form.endereco"
                :class="{ 'p-invalid': errors.endereco }"
                required
                rows="3"
              />
              <small v-if="errors.endereco" class="text-xs p-error">{{ errors.endereco[0] }}</small>
            </div>

            <div>
              <label for="data_cadastro" class="block mb-1 text-sm font-medium text-gray-700">Data de Cadastro</label>
              <Calendar
                id="data_cadastro"
                v-model="dataCadastro"
                dateFormat="dd/mm/yy"
                :showIcon="true"
                :placeholder="'dd/mm/aaaa'"
              />
            </div>
          </div>

          <div class="flex justify-end pt-4 space-x-3">
            <Button
              label="Cancelar"
              severity="secondary"
              @click="router.go(-1)"
              :disabled="loading"
            />
            <Button
              label="Salvar"
              icon="pi pi-check"
              type="submit"
              :loading="loading"
            />
          </div>
        </form>
      </template>
    </Card>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'primevue/usetoast'
import { produtorService } from '../services/produtorService'
import type { ProdutorFormData } from '../types/produtor'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Calendar from 'primevue/calendar'
import Button from 'primevue/button'

const router = useRouter()
const route = useRoute()
const toast = useToast()

const isEditing = computed(() => !!route.params.id)
const produtorId = computed(() => Number(route.params.id))

const form = ref<ProdutorFormData>({
  nome: '',
  cpf_cnpj: '',
  email: '',
  telefone: '',
  endereco: '',
  data_cadastro: ''
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)

function justNumber(event: Event) {
  const input = event.target as HTMLInputElement
  input.value = input.value.replace(/\D/g, '').slice(0, 14)
  form.value.cpf_cnpj = input.value
}

const loadProdutor = async () => {
  if (!isEditing.value) return
  try {
    const response = await produtorService.getById(produtorId.value)
    const produtor = response.data
    form.value = {
      nome: produtor.nome,
      cpf_cnpj: produtor.cpf_cnpj,
      email: produtor.email,
      telefone: produtor.telefone,
      endereco: produtor.endereco,
      data_cadastro: produtor.data_cadastro ? parseISODateToLocalDate(produtor.data_cadastro) : undefined
    }
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
    router.push('/produtores')
  }
}

const dataCadastro = computed<Date | undefined>({
  get() {
    if (!form.value.data_cadastro) return undefined
    return new Date(form.value.data_cadastro)
  },
  set(value: Date | undefined) {
    form.value.data_cadastro = value ? value.toISOString() : ''
  }
})

function parseISODateToLocalDate(dateString: string): Date | undefined {
  if (!dateString) return undefined

  const [datePart] = dateString.split('T')
  if (!datePart) return undefined

  const [yearStr, monthStr, dayStr] = datePart.split('-')
  const year = Number(yearStr), month = Number(monthStr), day = Number(dayStr)

  if (!yearStr || !monthStr || !dayStr) return undefined
  if (isNaN(year) || isNaN(month) || isNaN(day)) return undefined

  return new Date(year, month - 1, day)
}

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  const cpfCnpj = form.value.cpf_cnpj
  if (!/^\d{11}$/.test(cpfCnpj) && !/^\d{14}$/.test(cpfCnpj)) {
    errors.value.cpf_cnpj = ['Digite um CPF (11 dígitos) ou CNPJ (14 dígitos) válido, somente números.']
    loading.value = false
    return
  }

  try {
    if (isEditing.value) {
      await produtorService.update(produtorId.value, form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produtor atualizado com sucesso.', life: 3000 })
    } else {
      await produtorService.create(form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produtor criado com sucesso.', life: 3000 })
    }

    router.push('/produtores')
  } catch (error: unknown) {
    if (error instanceof Error) {
      toast.add({ severity: 'error', summary: 'Erro', detail: error.message, life: 3000 })
    } else {
      const message = extractErrorMessage(error)
      toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  if (isEditing.value) {
    loadProdutor()
  }
})
</script>

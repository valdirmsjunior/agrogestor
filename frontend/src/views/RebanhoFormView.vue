<template>
  <div class="p-6">
    <h1 class="mb-6 text-2xl font-bold">{{ isEditing ? 'Editar Rebanho' : 'Cadastrar Rebanho' }}</h1>

    <Card>
      <template #content>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="md:col-span-1">
              <label for="especie" class="block mb-1 text-sm font-medium text-gray-700">Espécie *</label>
              <Dropdown
                id="especie"
                class="w-full"
                v-model="form.especie"
                :options="especies"
                placeholder="Selecione uma espécie"
                :class="{ 'p-invalid': errors.especie }"
                required
              />
              <small v-if="errors.especie" class="text-xs p-error">{{ errors.especie[0] }}</small>
            </div>

            <div>
              <label for="quantidade" class="block mb-1 text-sm font-medium text-gray-700">Quantidade *</label>
              <InputNumber
                id="quantidade"
                class="w-full"
                v-model="form.quantidade"
                :class="{ 'p-invalid': errors.quantidade }"
                :min="0"
                required
              />
              <small v-if="errors.quantidade" class="text-xs p-error">{{ errors.quantidade[0] }}</small>
            </div>

            <div>
              <label for="finalidade" class="block mb-1 text-sm font-medium text-gray-700">Finalidade *</label>
              <InputText
                id="finalidade"
                class="w-full p-4 text-lg"
                v-model="form.finalidade"
                :class="{ 'p-invalid': errors.finalidade }"
                required
              />
              <small v-if="errors.finalidade" class="text-xs p-error">{{ errors.finalidade[0] }}</small>
            </div>

            <div class="md:col-span-1">
              <label for="propriedade_id" class="block mb-1 text-sm font-medium text-gray-700">Propriedade *</label>
              <Dropdown
                id="propriedade_id"
                class="w-full"
                v-model="form.propriedade_id"
                :options="propriedades"
                filter
                option-label="nome"
                option-value="id"
                placeholder="Selecione uma propriedade"
                :class="{ 'p-invalid': errors.propriedade_id }"
                required
              />
              <small v-if="errors.propriedade_id" class="text-xs p-error">{{ errors.propriedade_id[0] }}</small>
            </div>

            <div>
              <label for="data_atualizacao" class="block mb-1 text-sm font-medium text-gray-700">Data de Atualização</label>
              <Calendar
                id="data_atualizacao"
                class="w-full"
                v-model="dataAtualizacao"
                dateFormat="dd/mm/yy"
                :showIcon="true"
                :placeholder="'dd/mm/aaaa'"
              />
            </div>
          </div>
          <div class="flex justify-end pt-6 space-x-3">
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
import { rebanhoService } from '../services/rebanhoService'
import type { RebanhoFormData } from '../types/rebanho'
import { propriedadeService } from '../services/propriedadeService'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import InputNumber from 'primevue/inputnumber'
import InputText from 'primevue/inputtext'
import Calendar from 'primevue/calendar'

const router = useRouter()
const route = useRoute()
const toast = useToast()

const isEditing = computed(() => !!route.params.id)
const rebanhoId = computed(() => Number(route.params.id))

const especies = ['Suínos', 'Caprinos', 'Bovinos']

const form = ref<RebanhoFormData>({
  especie: '',
  quantidade: 0,
  finalidade: '',
  data_atualizacao: '',
  propriedade_id: 0
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)
const propriedades = ref<Array<{ id: number; nome: string }>>([])

const loadPropriedades = async () => {
  try {
    const response = await propriedadeService.getAll(1, {}, { field: 'nome', order: 'asc' })
    propriedades.value = response.data
  } catch (error: unknown) {
    const message = extractErrorMessage(error)
    toast.add({
      severity: 'error',
      summary: 'Erro ao carregar propriedades',
      detail: message,
      life: 4000
    })
    console.error('Erro ao carregar propriedades:', error)
  }
}

const loadRebanho = async () => {
  if (!isEditing.value) return
  try {
    const rebanho = await rebanhoService.getById(rebanhoId.value)
    form.value = {
      especie: rebanho.especie,
      quantidade: rebanho.quantidade,
      finalidade: rebanho.finalidade,
      data_atualizacao: rebanho.data_atualizacao ?  parseISODateToLocalDate(rebanho.data_atualizacao) : undefined,
      propriedade_id: rebanho.propriedade_id
    }
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  }
}

const dataAtualizacao = computed<Date | undefined>({
  get() {
    if (!form.value.data_atualizacao) return undefined
    return new Date(form.value.data_atualizacao)
  },
  set(value: Date | undefined) {
    form.value.data_atualizacao = value ? value.toISOString() : ''
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
  try {
    if (isEditing.value) {
      await rebanhoService.update(rebanhoId.value, form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Rebanho atualizado com sucesso.', life: 3000 })
    } else {
      await rebanhoService.create(form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Rebanho criado com sucesso.', life: 3000 })
    }
    router.push('/rebanhos')
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
  loadPropriedades()
  if (isEditing.value) {
    loadRebanho()
  }
})
</script>

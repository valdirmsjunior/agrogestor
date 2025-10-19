<template>
  <div class="p-6">
    <h1 class="mb-6 text-2xl font-bold">{{ isEditing ? 'Editar Propriedade' : 'Cadastrar Propriedade' }}</h1>

    <Card>
      <template #content>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="md:col-span-1">
              <label for="nome" class="block mb-1 text-sm font-medium text-gray-700">Nome *</label>
              <InputText
                id="nome"
                class="w-full p-4 text-lg"
                v-model="form.nome"
                :class="{ 'p-invalid': errors.nome }"
                required
              />
              <small v-if="errors.nome" class="text-xs p-error">{{ errors.nome[0] }}</small>
            </div>

            <div>
              <label for="municipio" class="block mb-1 text-sm font-medium text-gray-700">Município *</label>
              <InputText
                id="municipio"
                class="w-full p-4 text-lg"
                v-model="form.municipio"
                :class="{ 'p-invalid': errors.municipio }"
                required
              />
              <small v-if="errors.municipio" class="text-xs p-error">{{ errors.municipio[0] }}</small>
            </div>

            <div>
              <label for="uf" class="block mb-1 text-sm font-medium text-gray-700">UF *</label>
              <InputText
                id="uf"
                v-model="form.uf"
                type="uf"
                :class="{ 'p-invalid': errors.uf }"
                required
              />
              <small v-if="errors.uf" class="text-xs p-error">{{ errors.uf[0] }}</small>
            </div>

            <div>
              <label for="inscricao_estadual" class="block mb-1 text-sm font-medium text-gray-700">Inscrição Estadual *</label>
              <InputText
                id="inscricao_estadual"
                class="w-full p-4 text-lg"
                v-model="form.inscricao_estadual"
                :class="{ 'p-invalid': errors.inscricao_estadual }"
                required
              />
              <small v-if="errors.inscricao_estadual" class="text-xs p-error">{{ errors.inscricao_estadual[0] }}</small>
            </div>

            <div class="md:col-span-1">
              <label for="area_total" class="block mb-1 text-sm font-medium text-gray-700">Area Total *</label>
              <InputNumber
                id="area_total"
                class="w-full text-lg"
                v-model="form.area_total"
                :class="{ 'p-invalid': errors.area_total }"
                mode="decimal"
                :min-fraction-digits="2"
                :max-fraction-digits="2"
                required
              />
              <small v-if="errors.area_total" class="text-xs p-error">{{ errors.area_total[0] }}</small>
            </div>

            <div class="mb-4 md:col-span-1">
              <label for="produtor_id" class="block mb-1 text-sm font-medium text-gray-700">Produtor *</label>
              <Dropdown
                id="produtor_id"
                class="w-full"
                v-model="form.produtor_id"
                :options="produtores"
                filter
                option-label="nome"
                option-value="id"
                placeholder="Selecione um produtor"
                :class="{ 'p-invalid': errors.produtor_id }"
                required
              />
              <small v-if="errors.produtor_id" class="text-xs p-error">{{ errors.produtor_id[0] }}</small>
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
import { propriedadeService } from '../services/propriedadeService'
import type { PropriedadeFormData } from '../types/propriedade'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import InputNumber from 'primevue/inputnumber'

import { produtorService } from '../services/produtorService'

const router = useRouter()
const route = useRoute()
const toast = useToast()

const isEditing = computed(() => !!route.params.id)
const propriedadeId = computed(() => Number(route.params.id))

const form = ref<PropriedadeFormData>({
  nome: '',
  municipio: '',
  uf: '',
  inscricao_estadual: '',
  area_total: 0,
  produtor_id: 0
})

const errors = ref<Record<string, string[]>>({})
const loading = ref(false)
const produtores = ref<Array<{ id: number; nome: string }>>([])

const loadProdutores = async () => {
  try {
    const response = await produtorService.getAll(1, {}, { field: 'nome', order: 'asc' })
    produtores.value = response.data
  } catch (error: unknown) {
    const message = extractErrorMessage(error)
    toast.add({
      severity: 'error',
      summary: 'Erro ao carregar produtores',
      detail: message,
      life: 4000
    })
    console.error('Erro ao carregar produtores:', error)
  }
}

const loadPropriedade = async () => {
  if (!isEditing.value) return
  try {
    const propriedade = await propriedadeService.getById(propriedadeId.value)
    form.value = {
      nome: propriedade.nome,
      municipio: propriedade.municipio,
      uf: propriedade.uf,
      inscricao_estadual: propriedade.inscricao_estadual,
      area_total: propriedade.area_total,
      produtor_id: propriedade.produtor_id
    }
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
    // router.push('/propriedades')
  }
}

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}
  try {
    if (isEditing.value) {
      await propriedadeService.update(propriedadeId.value, form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Propriedade atualizada com sucesso.', life: 3000 })
    } else {
      await propriedadeService.create(form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Propriedade criada com sucesso.', life: 3000 })
    }
    router.push('/propriedades')
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
  loadProdutores()
  if (isEditing.value) {
    loadPropriedade()
  }
})
</script>

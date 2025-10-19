<template>
  <div class="p-6">
    <h1 class="mb-6 text-2xl font-bold">{{ isEditing ? 'Editar Unidade de Produção' : 'Cadastrar Unidade de Produção' }}</h1>

    <Card>
      <template #content>
        <form @submit.prevent="handleSubmit" class="space-y-4">
          <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

            <div class="md:col-span-1">
              <label for="nome_cultura" class="block mb-1 text-sm font-medium text-gray-700">Cultura *</label>
              <Dropdown
                id="nome_cultura"
                class="w-full"
                v-model="form.nome_cultura"
                :options="culturas"
                placeholder="Selecione uma cultura"
                :class="{ 'p-invalid': errors.nome_cultura }"
                required
              />
              <small v-if="errors.nome_cultura" class="text-xs p-error">{{ errors.nome_cultura[0] }}</small>
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
              <label for="area_total_ha" class="block mb-1 text-sm font-medium text-gray-700">Área (ha) *</label>
              <InputNumber
                id="area_total_ha"
                v-model="form.area_total_ha"
                :class="{ 'p-invalid': errors.area_total_ha }"
                mode="decimal"
                :min-fraction-digits="2"
                :max-fraction-digits="2"
                required
              />
              <small v-if="errors.area_total_ha" class="text-xs p-error">{{ errors.area_total_ha[0] }}</small>
            </div>

            <div class="md:col-span-2">
              <label class="block mb-2 text-sm font-medium text-gray-700">Coordenadas Geográficas:</label>
              <div class="grid grid-cols-1 gap-2 mb-5 sm:grid-cols-2">
                <div>
                  <label for="lat" class="text-xs">Latitude: </label>
                  <InputNumber
                    id="lat"
                    v-model="coordenadas.lat"
                    :class="{ 'p-invalid': errors.coordenadas_geograficas }"
                    mode="decimal"
                    :min-fraction-digits="6"
                    :max-fraction-digits="6"
                  />
                </div>
                <div>
                  <label for="lng" class="text-xs">Longitude: </label>
                  <InputNumber
                    id="lng"
                    v-model="coordenadas.lng"
                    :class="{ 'p-invalid': errors.coordenadas_geograficas }"
                    mode="decimal"
                    :min-fraction-digits="6"
                    :max-fraction-digits="6"
                  />
                </div>
              </div>
              <small v-if="errors.coordenadas_geograficas" class="text-xs p-error">{{ errors.coordenadas_geograficas[0] }}</small>
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
import { ref, onMounted, computed, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'primevue/usetoast'
import { unidadeProducaoService } from '../services/unidadeProducaoService'
import type { UnidadeProducaoFormData } from '../types/unidadeProducao'
import { propriedadeService } from '../services/propriedadeService'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import Button from 'primevue/button'
import Dropdown from 'primevue/dropdown'
import InputNumber from 'primevue/inputnumber'

const router = useRouter()
const route = useRoute()
const toast = useToast()

const isEditing = computed(() => !!route.params.id)
const unidadeId = computed(() => Number(route.params.id))

const culturas = [
  'Laranja Pera',
  'Melancia Crimson Sweet',
  'Goiaba Paluma'
]

const form = ref<UnidadeProducaoFormData>({
  nome_cultura: '',
  area_total_ha: 0,
  coordenadas_geograficas: undefined,
  propriedade_id: 0
})

const coordenadas = reactive({
  lat: null as number | null,
  lng: null as number | null
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

const loadUnidade = async () => {
  if (!isEditing.value) return
  try {
    const unidade = await unidadeProducaoService.getById(unidadeId.value)
    form.value = {
      nome_cultura: unidade.nome_cultura,
      area_total_ha: unidade.area_total_ha,
      coordenadas_geograficas: unidade.coordenadas_geograficas || undefined,
      propriedade_id: unidade.propriedade_id
    }

    if (unidade.coordenadas_geograficas) {
      coordenadas.lat = unidade.coordenadas_geograficas.lat
      coordenadas.lng = unidade.coordenadas_geograficas.lng
    }
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
    // router.push('/unidade-producao')
  }
}

const handleSubmit = async () => {
  if (coordenadas.lat !== null && coordenadas.lng !== null) {
    form.value.coordenadas_geograficas = {
      lat: coordenadas.lat,
      lng: coordenadas.lng
    }
  } else {
    form.value.coordenadas_geograficas = undefined
  }

  loading.value = true
  errors.value = {}
  try {
    if (isEditing.value) {
      await unidadeProducaoService.update(unidadeId.value, form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Unidade de produção atualizada com sucesso.', life: 3000 })
    } else {
      await unidadeProducaoService.create(form.value)
      toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Unidade de produção criada com sucesso.', life: 3000 })
    }
    router.push('/unidades-producao')
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
    loadUnidade()
  }
})
</script>

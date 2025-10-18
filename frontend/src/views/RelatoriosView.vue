<template>
  <div class="p-6">
    <h1 class="mb-6 text-2xl font-bold">Relatórios</h1>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
      <Card>
        <template #title>Propriedades por Município</template>
        <template #content>
          <div class="space-y-2 overflow-y-auto max-h-96">
            <div v-for="item in relatorios.propriedades_por_municipio" :key="item.municipio + item.uf" class="flex justify-between">
              <span>{{ item.municipio }} - {{ item.uf }}</span>
              <span class="font-medium">{{ item.total }}</span>
            </div>
            <div v-if="relatorios.propriedades_por_municipio.length === 0" class="text-gray-500">
              Nenhum dado encontrado
            </div>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Animais por Espécie</template>
        <template #content>
          <div class="space-y-2 overflow-y-auto max-h-96">
            <div v-for="item in relatorios.animais_por_especie" :key="item.especie" class="flex justify-between">
              <span>{{ item.especie }}</span>
              <span class="font-medium">{{ item.total }}</span>
            </div>
            <div v-if="relatorios.animais_por_especie.length === 0" class="text-gray-500">
              Nenhum dado encontrado
            </div>
          </div>
        </template>
      </Card>

      <Card>
        <template #title>Hectares por Cultura</template>
        <template #content>
          <div class="space-y-2 overflow-y-auto max-h-96">
            <div v-for="item in relatorios.hectares_por_cultura" :key="item.nome_cultura" class="flex justify-between">
              <span>{{ item.nome_cultura }}</span>
              <span class="font-medium">{{ item.total_ha }} ha</span>
            </div>
            <div v-if="relatorios.hectares_por_cultura.length === 0" class="text-gray-500">
              Nenhum dado encontrado
            </div>
          </div>
        </template>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import { relatorioService, type RelatorioResponse } from '../services/relatorioService'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'

const toast = useToast()
const relatorios = ref<RelatorioResponse>({
  propriedades_por_municipio: [],
  animais_por_especie: [],
  hectares_por_cultura: []
})
const loading = ref(false)

const loadRelatorios = async () => {
  loading.value = true
  try {
    const data = await relatorioService.getRelatorios()
    relatorios.value = data
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadRelatorios()
})
</script>

<template>
  <div class="p-6 mx-auto max-w-7xl">
    <h1 class="mb-10 text-4xl font-extrabold text-gray-800">Dashboard</h1>

    <div class="flex flex-col items-center p-8 mb-12 bg-white shadow-xl rounded-xl">
      <h2 class="flex items-center gap-3 mb-6 text-2xl font-semibold text-blue-700">
        <i class="text-3xl text-blue-600 pi pi-map-marker"></i>Propriedades por Município
      </h2>
      <div class="w-full flex justify-center min-h-[320px]">
        <BarChart
          v-if="chartData.propriedades.labels.length"
          :data="chartData.propriedades"
          :options="{ responsive: true, plugins: { legend: { position: 'top' } } }"
          height="100"
        />
        <div v-else class="w-full py-12 text-center text-gray-500">Sem dados</div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-8 md:grid-cols-2">
      <div class="flex flex-col items-center p-8 bg-white shadow-xl rounded-xl">
        <h2 class="flex items-center gap-3 mb-6 text-2xl font-semibold text-green-700">
          <i class="text-3xl text-green-600 pi pi-paw"></i>Animais por Espécie
        </h2>
        <div class="w-full flex justify-center min-h-[220px]">
          <PieChart
            v-if="chartData.animais.labels.length"
            :data="chartData.animais"
            :options="{ responsive: true }"
            :height="150"
            :width="300"
          />
          <div v-else class="w-full py-8 text-center text-gray-500">Sem dados</div>
        </div>
      </div>
      
      <div class="flex flex-col items-center p-8 bg-white shadow-xl rounded-xl">
        <h2 class="flex items-center gap-3 mb-6 text-2xl font-semibold text-purple-700">
          <i class="text-3xl text-purple-600 pi pi-leaf"></i>Hectares por Cultura
        </h2>
        <div class="w-full flex justify-center min-h-[220px]">
          <DoughnutChart
            v-if="chartData.hectares.labels.length"
            :data="chartData.hectares"
            :options="{ responsive: true }"
            :height="150"
            :width="300"
          />
          <div v-else class="w-full py-8 text-center text-gray-500">Sem dados</div>
        </div>
      </div>
    </div>
  </div>
</template>



<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToast } from 'primevue/usetoast'
import { relatorioService, type RelatorioCultura, type RelatorioEspecie, type RelatorioPropriedade } from '@/services/relatorioService'
import { extractErrorMessage } from '@/utils/errorHandler'

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale,
  ArcElement
} from 'chart.js'
import { Bar as BarChart, Pie as PieChart, Doughnut as DoughnutChart } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale, ArcElement)

const toast = useToast()
const chartData = ref({
  propriedades: {
    labels: [] as string[],
    datasets: [{
      label: 'Propriedades',
      data: [] as number[],
      backgroundColor: '#3b82f6'
    }]
  },
  animais: {
    labels: [] as string[],
    datasets: [{
      label: 'Animais',
      data: [] as number[],
      backgroundColor: ['#10b981', '#f59e0b', '#ef4444']
    }]
  },
  hectares: {
    labels: [] as string[],
    datasets: [{
      label: 'Hectares',
      data: [] as number[],
      backgroundColor: ['#8b5cf6', '#ec4899', '#06b6d4']
    }]
  }
})

const loadRelatorios = async () => {
  try {
    const data = await relatorioService.getRelatorios()
    const datasetPropriedades = chartData.value.propriedades.datasets[0]
    const datasetAnimais = chartData.value.animais.datasets[0]
    const datasetHectares = chartData.value.hectares.datasets[0]

    chartData.value.propriedades.labels = data.propriedades_por_municipio.map((p: RelatorioPropriedade) => `${p.municipio} - ${p.uf}`)
    if (datasetPropriedades) {
      datasetPropriedades.data = data.propriedades_por_municipio.map((p: RelatorioPropriedade) => p.total)
    }

    chartData.value.animais.labels = data.animais_por_especie.map((a: RelatorioEspecie) => a.especie)
    if (datasetAnimais) {
      datasetAnimais.data = data.animais_por_especie.map((a: RelatorioEspecie) => a.total)
    }

    chartData.value.hectares.labels = data.hectares_por_cultura.map((h: RelatorioCultura) => h.nome_cultura)
    if (datasetHectares) {
      datasetHectares.data = data.hectares_por_cultura.map((h: RelatorioCultura) => h.total_ha)
    }
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  }
}

onMounted(() => {
  loadRelatorios()
})
</script>

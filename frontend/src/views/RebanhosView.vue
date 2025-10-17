<template>

  <div class="p-6">
  <h1 class="mb-6 text-2xl font-bold">Rebanhos</h1>

  <div class="p-4 mb-6 bg-white rounded-lg shadow">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <Dropdown
        v-model="filters.especie"
        id="especies"
        :options="especies"
        placeholder="Espécie"
        @change="applyFilters"
        class="w-full"
      />
      <InputText
        v-model="filters.municipio"
        placeholder="Município da propriedade"
        @keyup.enter="applyFilters"
      />
      <Button label="Filtrar" icon="pi pi-search" @click="applyFilters" />
    </div>
  </div>

  <Card>
    <template #title>
      <div class="flex items-center justify-between">
        <span>Lista de Rebanhos</span>
        <Button label="Adicionar Rebanho" icon="pi pi-plus" @click="() => router.push('/rebanhos/novo')" />
      </div>
    </template>
    <template #content>
      <DataTable
        :value="rebanhos"
        :paginator="true"
        :rows="10"
        :total-records="totalRecords"
        :lazy="true"
        :loading="loading"
        @page="onPage"
        @sort="onSort"
        :first="(currentPage - 1) * 10"
        sortMode="single"
        selectionMode="single"
        dataKey="id"
        class="w-full"
      >
        <Column field="id" header="ID" style="width: 80px" :sortable="true"></Column>
        <Column field="especie" header="Espécie" :sortable="true"></Column>
        <Column field="quantidade" header="Quantidade" :sortable="true" style="width: 120px"></Column>
        <Column field="finalidade" header="Finalidade" :sortable="true"></Column>

        <Column field="propriedade.nome" header="Propriedade" :sortable="true">
          <template #body="slotProps">
              {{ slotProps.data.propriedade?.nome || '—' }}
            </template>
        </Column>

        <Column field="propriedade.municipio" header="Município" sortable>
            <template #body="slotProps">
              {{ slotProps.data.propriedade?.municipio || '—' }}
            </template>
          </Column>

        <Column header="Ações" style="width: 120px">
          <template #body="slotProps">
            <Button
              icon="pi pi-pencil"
              text
              severity="secondary"
              @click="editRebanho(slotProps.data.id)"
              class="mr-2"
            />
            <Button
              icon="pi pi-trash"
              text
              severity="danger"
              @click="confirmDelete(slotProps.data.id)"
            />
          </template>
        </Column>
      </DataTable>
    </template>
  </Card>
</div>

</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useConfirm } from 'primevue/useconfirm'
import { useToast } from 'primevue/usetoast'
import { rebanhoService } from '../services/rebanhoService'
import type { DataTablePageEvent, DataTableSortEvent } from 'primevue/datatable'
import type { Rebanho } from '../types/rebanho'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown'


const router = useRouter()
const confirm = useConfirm()
const toast = useToast()

const especies = ['Suínos', 'Caprinos', 'Bovinos']

const rebanhos = ref<Rebanho[]>([])
const totalRecords = ref(0)
const loading = ref(false)
const currentPage = ref(1)
const filters = ref({
  especie: '',
  municipio: ''
})

const sortField = ref<string | null>(null)
const sortOrder = ref<'asc' | 'desc' | null>(null)

const loadData = async (page: number = 1) => {
  loading.value = true
  try {
    const sort = sortField.value && sortOrder.value ? { field: sortField.value, order: sortOrder.value } : undefined
    const response = await rebanhoService.getAll(page, filters.value, sort)
    rebanhos.value = response.data
    totalRecords.value = response.meta.total
    currentPage.value = page
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  } finally {
    loading.value = false
  }
}

const applyFilters = () => {
  loadData(1)
}

const onSort = (event: DataTableSortEvent) => {
  if (typeof event.sortField === 'string') {
    sortField.value = event.sortField
  } else {
    sortField.value = null
  }
  sortOrder.value = event.sortOrder === 1 ? 'asc' : event.sortOrder === -1 ? 'desc' : null
  loadData(currentPage.value)
}

const onPage = (event: DataTablePageEvent) => {
  loadData(event.page + 1)
}

const editRebanho = (id: number) => {
  router.push(`/rebanhos/${id}/editar`)
}

const confirmDelete = (id: number) => {
  confirm.require({
    message: 'Tem certeza que deseja excluir este rebanho?',
    header: 'Confirmação',
    icon: 'pi pi-info-circle',
    accept: () => deleteRebanho(id)
  })
}

const deleteRebanho = async (id: number) => {
  try {
    await rebanhoService.delete(id)
    loadData(currentPage.value)
    toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Rebanho excluído com sucesso.', life: 3000 })
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  }
}

onMounted(() => {
  loadData()
})
</script>

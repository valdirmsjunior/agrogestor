<template>

  <div class="p-6">
  <h1 class="mb-6 text-2xl font-bold">Propriedades</h1>

  <div class="p-4 mb-6 bg-white rounded-lg shadow">
    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
      <InputText
        v-model="filters.nome"
        placeholder="Nome da Propriedade"
        @keyup.enter="applyFilters"
      />
      <InputText
        v-model="filters.municipio"
        placeholder="Município"
        @keyup.enter="applyFilters"
      />
      <Button label="Filtrar" icon="pi pi-search" @click="applyFilters" />
    </div>
  </div>

  <Card>
    <template #title>
      <div class="flex items-center justify-between">
        <span>Lista de Propriedades</span>
        <Button label="Adicionar Propriedade" icon="pi pi-plus" @click="() => router.push('/propriedades/novo')" />
      </div>
    </template>
    <template #content>
      <DataTable
        :value="propriedades"
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
        <Column field="id" header="ID" style="width: 100px"></Column>
        <Column field="nome" header="Nome" :sortable="true"></Column>
        <Column field="municipio" header="Município" :sortable="true"></Column>
        <Column field="uf" header="UF" :sortable="true"></Column>
        <Column field="area_total" header="Área Total(ha)" :sortable="true"></Column>
        <Column field="inscricao_estadual" header="Inscrição Estadual" :sortable="true"></Column>
        <Column field="produtor_id" header="Produtor" :sortable="true">
          <template #body="slotProps">
              {{ slotProps.data.produtor?.nome || '—' }}
            </template>
        </Column>
        <Column header="Ações" style="width: 120px">
          <template #body="slotProps">
            <Button
              icon="pi pi-pencil"
              text
              severity="secondary"
              @click="editPropriedade(slotProps.data.id)"
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
import { propriedadeService } from '../services/propriedadeService'
import type { Propriedade } from '../types/propriedade'
import type { DataTablePageEvent, DataTableSortEvent } from 'primevue/datatable'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'

const router = useRouter()
const confirm = useConfirm()
const toast = useToast()

const propriedades = ref<Propriedade[]>([])
const totalRecords = ref(0)
const loading = ref(false)
const currentPage = ref(1)
const filters = ref({
  nome: '',
  municipio: ''
})

const sortField = ref<string | null>(null)
const sortOrder = ref<'asc' | 'desc' | null>(null)

const loadData = async (page: number = 1) => {
  loading.value = true
  try {
    const sort = sortField.value && sortOrder.value ? { field: sortField.value, order: sortOrder.value } : undefined
    const response = await propriedadeService.getAll(page, filters.value, sort)
    propriedades.value = response.data
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

const editPropriedade = (id: number) => {
  router.push(`/propriedades/${id}/editar`)
}

const confirmDelete = (id: number) => {
  confirm.require({
    message: 'Tem certeza que deseja excluir esta propriedade?',
    header: 'Confirmação',
    icon: 'pi pi-info-circle',
    accept: () => deletePropriedade(id)
  })
}

const deletePropriedade = async (id: number) => {
  try {
    await propriedadeService.delete(id)
    loadData(currentPage.value)
    toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Propriedade excluída', life: 3000 })
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  }
}

onMounted(() => {
  loadData()
})
</script>

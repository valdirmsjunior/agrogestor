<template>
  <div class="p-6">
    <h1 class="mb-6 text-2xl font-bold">Produtores</h1>

    <div class="p-4 mb-6 bg-white rounded-lg shadow">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        <InputText
          v-model="filters.nome"
          placeholder="Nome"
          @keyup.enter="applyFilters"
        />
        <InputText
          v-model="filters.cpf_cnpj"
          placeholder="CPF/CNPJ"
          maxlength="14"
          inputmode="numeric"
          pattern="[0-9]*"
          @input="justNumber"
          @keyup.enter="applyFilters"
        />
        <Button label="Filtrar" icon="pi pi-search" @click="applyFilters" />
      </div>
    </div>

    <Card>
      <template #title>
        <div class="flex items-center justify-between">
          <span>Lista de Produtores</span>
          <Button label="Adicionar Produtor" icon="pi pi-plus" @click="() => router.push('/produtores/novo')" />
        </div>
      </template>
      <template #content>
        <DataTable
          :value="produtores"
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
          <Column field="cpf_cnpj" header="CPF/CNPJ" :sortable="true"></Column>
          <Column field="email" header="E-mail" :sortable="true"></Column>
          <Column field="telefone" header="Telefone" :sortable="true"></Column>
          <Column header="Ações" style="width: 120px">
            <template #body="slotProps">
              <Button
                icon="pi pi-pencil"
                text
                severity="secondary"
                @click="editProdutor(slotProps.data.id)"
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
import { produtorService } from '../services/produtorService'
import type { Produtor } from '../types/produtor'
import type { DataTablePageEvent } from 'primevue/datatable'
import { extractErrorMessage } from '../utils/errorHandler'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import type { DataTableSortEvent } from 'primevue/datatable'

const router = useRouter()
const confirm = useConfirm()
const toast = useToast()

const produtores = ref<Produtor[]>([])
const totalRecords = ref(0)
const loading = ref(false)
const currentPage = ref(1)
const filters = ref({
  nome: '',
  cpf_cnpj: ''
})

const sortField = ref<string | null>(null)
const sortOrder = ref<'asc' | 'desc' | null>(null)

function justNumber(event: Event) {
  const input = event.target as HTMLInputElement
  input.value = input.value.replace(/\D/g, '').slice(0, 14)
  filters.value.cpf_cnpj = input.value
}

const loadData = async (page: number = 1) => {
  loading.value = true
  try {
    const sort = sortField.value && sortOrder.value ? { field: sortField.value, order: sortOrder.value } : undefined
    const response = await produtorService.getAll(page, filters.value, sort)
    produtores.value = response.data
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

const editProdutor = (id: number) => {
  router.push(`/produtores/${id}/editar`)
}

const confirmDelete = (id: number) => {
  confirm.require({
    message: 'Tem certeza que deseja excluir este produtor?',
    header: 'Confirmação',
    icon: 'pi pi-info-circle',
    accept: () => deleteProdutor(id)
  })
}

const deleteProdutor = async (id: number) => {
  try {
    await produtorService.delete(id)
    loadData(currentPage.value)
    toast.add({ severity: 'success', summary: 'Sucesso', detail: 'Produtor excluído', life: 3000 })
  } catch (error) {
    const message = extractErrorMessage(error)
    toast.add({ severity: 'error', summary: 'Erro', detail: message, life: 3000 })
  }
}

onMounted(() => {
  loadData()
})
</script>

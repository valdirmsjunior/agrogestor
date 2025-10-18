<template>
  <div class="flex min-h-screen bg-gray-100">
    <!-- Sidebar -->
    <aside class="flex flex-col px-4 py-8 bg-white shadow-lg w-60">
      <h2 class="mb-8 text-xl font-bold text-green-700">AgroGestor</h2>
      <hr class="mb-10">
      <nav class="flex-1">
        <ul class="space-y-1">
          <li>
            <RouterLink
              to="/produtores"
              class="flex items-center px-3 py-2 text-gray-700 transition-colors rounded hover:bg-blue-100 hover:text-gray-600"
              :class="{ 'bg-gray-100 text-gray-700 font-bold': isActive('/produtores') }"
            >
              <i class="mr-3 text-lg pi pi-users"></i>
              Produtores
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/propriedades"
              class="flex items-center px-3 py-2 text-gray-700 transition-colors rounded hover:bg-blue-100 hover:text-gray-600"
              :class="{ 'bg-gray-100 text-gray-700 font-bold': isActive('/propriedades') }"
            >
              <i class="mr-3 text-lg pi pi-home"></i>
              Propriedades
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/unidades-producao"
              class="flex items-center px-3 py-2 text-gray-700 transition-colors rounded hover:bg-blue-100 hover:text-gray-600"
              :class="{ 'bg-gray-100 text-gray-700 font-bold': isActive('/unidades-producao') }"
            >
              <i class="mr-3 text-lg pi pi-sliders-h"></i>
              Unidades de Produção
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/rebanhos"
              class="flex items-center px-3 py-2 text-gray-700 transition-colors rounded hover:bg-blue-100 hover:text-gray-600"
              :class="{ 'bg-gray-100 text-gray-700 font-bold': isActive('/rebanhos') }"
            >
              <i class="mr-3 text-lg pi pi-verified"></i>
              Rebanhos
            </RouterLink>
          </li>
          <li>
            <RouterLink
              to="/relatorios"
              class="flex items-center px-3 py-2 text-gray-700 transition-colors rounded hover:bg-blue-100 hover:text-gray-600"
              :class="{ 'bg-gray-100 text-gray-700 font-bold': isActive('/relatorios') }"
            >
              <i class="mr-3 text-lg pi pi-chart-bar"></i>
              Relatórios
            </RouterLink>
          </li>
        </ul>
      </nav>
    </aside>
    <!-- Main Content -->
    <section class="flex-1 p-8 overflow-y-auto">
      <RouterView />
    </section>
  </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'

const route = useRoute()
function isActive(path: string) {
  return route.path.startsWith(path)
}
</script>

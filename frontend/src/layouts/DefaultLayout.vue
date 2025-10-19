<template>
  <div class="flex min-h-screen bg-gray-100">

    <aside :class="['flex flex-col px-4 py-8 bg-white shadow-lg transition-all duration-300 relative', isCollapsed ? 'w-16' : 'w-60']">

      <button
        class="absolute flex items-center justify-center w-8 h-8 bg-green-100 rounded-full shadow cursor-pointer -right-4 top-4"
        @click="toggleSidebar"
        :title="isCollapsed ? 'Expandir' : 'Encolher'"
      >
        <span v-if="isCollapsed">▶</span>
        <span v-else>◀</span>
      </button>
      <h2 v-if="!isCollapsed" class="mb-8 text-xl font-bold text-green-700">AgroGestor</h2>
      <hr v-if="!isCollapsed" class="mb-10">
      <nav class="flex-1">
        <ul class="space-y-1">
          <li v-for="item in menuItems" :key="item.to">
            <RouterLink
              :to="item.to"
              class="flex items-center px-3 py-2 text-gray-700 transition-colors rounded hover:bg-blue-100 hover:text-gray-600"
              :class="{ 'bg-gray-100 text-gray-700 font-bold': isActive(item.to) }"
            >
              <i :class="['mr-3 text-lg pi', item.icon]" />
              <span v-if="!isCollapsed">{{ item.label }}</span>
            </RouterLink>
          </li>
        </ul>
      </nav>
    </aside>
    <section class="flex-1 p-8 overflow-y-auto">
      <RouterView />
    </section>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRoute } from 'vue-router'

const isCollapsed = ref(false)
function toggleSidebar() {
  isCollapsed.value = !isCollapsed.value
}

const route = useRoute()
function isActive(path: string) {
  return route.path.startsWith(path)
}

const menuItems = [
  { to: '/produtores', label: 'Produtores', icon: 'pi-users' },
  { to: '/propriedades', label: 'Propriedades', icon: 'pi-home' },
  { to: '/unidades-producao', label: 'Unidades de Produção', icon: 'pi-sliders-h' },
  { to: '/rebanhos', label: 'Rebanhos', icon: 'pi-verified' },
  { to: '/relatorios', label: 'Relatórios', icon: 'pi-chart-bar' },
]
</script>

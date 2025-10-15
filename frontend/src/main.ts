import './assets/main.css'

import 'primevue/resources/themes/aura-light-green/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config'

const app = createApp(App)

app.use(PrimeVue, {
  ripple: true,
  inputStyle: 'outlined',
})

app.use(createPinia())
app.use(router)

app.mount('#app')

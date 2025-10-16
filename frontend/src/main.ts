import './assets/main.css'

import 'primevue/resources/themes/aura-light-green/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import PrimeVue from 'primevue/config'
import ConfirmationService from 'primevue/confirmationservice'
import ConfirmDialog from 'primevue/confirmdialog'
import ToastService from 'primevue/toastservice'
import Toast from 'primevue/toast'

const app = createApp(App)

app.use(PrimeVue, {
  ripple: true,
  inputStyle: 'outlined',
})
app.use(ConfirmationService)
app.use(ToastService)

app.use(createPinia())
app.use(router)

app.component('ConfirmDialog', ConfirmDialog)
app.component('Toast', Toast)

app.mount('#app')

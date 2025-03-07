import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

const app = createApp(App);

app.config.globalProperties.$apiBaseUrl = "http://127.0.0.1:8080/api";

app.mount('#app')

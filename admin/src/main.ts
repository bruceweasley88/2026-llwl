import './permission'
import './styles/index.scss'
import 'virtual:svg-icons-register'

import { createApp } from 'vue'

import { getConfig } from './api/app'
import App from './App.vue'
import install from './install'

const app = createApp(App)
app.use(install)
app.mount('#app')

getConfig().then((res) => {
    console.log(`%c 后台管理系统 %c v${res.version} `, 'padding: 4px 1px; border-radius: 3px 0 0 3px; color: #fff; background: #6366f1; font-weight: bold;', 'padding: 4px 1px; border-radius: 0 3px 3px 0; color: #fff; background: #8b5cf6; font-weight: bold;')
})

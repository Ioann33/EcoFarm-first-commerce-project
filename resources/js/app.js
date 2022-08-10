import './bootstrap';
// import './bootstrap.min'
// import './custom'
// import './custom'

import { createApp } from 'vue';
// import { createPinia } from 'pinia'

import App from './Page/App'
import router from './router/router'




createApp(App)
    .use(router)
//    .use(createPinia())
    .mount("#app")

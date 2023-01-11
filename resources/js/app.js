import './bootstrap';
// sintaks dibawah adalah milik vue
import { createApp } from 'vue';
// keluar dari views/welcome.blade lalu masuk ke App.vue
import App from '../js/App.vue';
import Css from '../css/app.css';
// mungkin ini dari dokumentasi vue
import router from './router';

// kode app.vue akan masuk ke #app milik welcome
createApp(App).use(router).mount('#app')

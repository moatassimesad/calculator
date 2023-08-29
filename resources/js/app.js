import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler';
import Calculator from '../components/Calculator.vue';

createApp({
    components: {
        Calculator,
    }
}).mount('#app');

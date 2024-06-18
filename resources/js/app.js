import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import PrimeVue from 'primevue/config';
import MultiSelect from 'primevue/multiselect';
import 'primevue/resources/themes/saga-blue/theme.css';       // Theme
import 'primevue/resources/primevue.min.css';                 // Core CSS // Icons

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) });

        vueApp.use(plugin);
        vueApp.use(ZiggyVue);
        vueApp.use(PrimeVue);
        vueApp.component('MultiSelect', MultiSelect);

        vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

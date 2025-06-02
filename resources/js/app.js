import '../css/app.css';
import './bootstrap';
import 'sweetalert2/dist/sweetalert2.min.css'


import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// resources/js/app.js
import { formatDistanceToNow } from 'date-fns';



createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue);

        // Add global filters
        app.config.globalProperties.$filters = {
            timeAgo(dateString) {
                return formatDistanceToNow(new Date(dateString), { addSuffix: true });
            }
        };

        return app.mount(el);
    },
    progress: {
        color: '#6366f1',
    },
});
